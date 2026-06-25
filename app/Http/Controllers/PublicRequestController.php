<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Category;
use App\Models\FcmToken;
use App\Models\Location;
use App\Models\InfrastructureUnit;
use App\Models\RequestUnit;
use Illuminate\Http\Request as HttpRequest;
use App\Models\User;
use App\Services\FirebaseService;
use Filament\Notifications\Notification;
use Filament\Actions\Action as NotificationAction;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;

class PublicRequestController extends Controller
{
    public function create()
    {
        return view('public-request', [
            'categories' => Category::all(),
            'locations'  => Location::all(),
        ]);
    }

    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'request_date'        => 'nullable|date',
            'requester_name'      => 'required|string|max:100',
            'requester_contact'   => 'nullable|string|max:50',
            'location_id'         => 'required|exists:locations,id',
            'broken_unit_ids'     => 'nullable|array',
            'broken_unit_ids.*'   => 'exists:good_units,id',
            'damaged_quantity'    => 'nullable|integer|min:0',
            'description'         => 'required|string|max:2000',
            'priority'            => 'nullable|string|in:Rendah,Sedang,Tinggi',
            'photo'               => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:8192',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ],
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $validated['photo'] = ImageService::compress($request->file('photo'), 'requests');
        }

        // ✅ Auto-fill category_id dari unit pertama
        $brokenUnitIds = $validated['broken_unit_ids'] ?? [];
        unset($validated['broken_unit_ids']);

        if (!empty($brokenUnitIds)) {
            $unit = \App\Models\GoodUnit::with('good')->find($brokenUnitIds[0]);
            $validated['category_id'] = $unit?->good?->goods_category_id ?? null;
        } else {
            $validated['category_id'] = null;
        }

        $validated['priority']         = $validated['priority'] ?? 'Rendah';
        $validated['status']           = 'Pending';
        $validated['request_date']     = $validated['request_date'] ?? now();
        $validated['damaged_quantity'] = count($brokenUnitIds) ?: ($validated['damaged_quantity'] ?? 1);

        $requestModel = Request::create($validated);

        // ✅ Simpan unit rusak & update statusnya
        foreach ($brokenUnitIds as $unitId) {
            RequestUnit::create([
                'request_id' => $requestModel->id,
                'unit_id'    => $unitId,
                'type'       => 'rusak',
            ]);

            InfrastructureUnit::find($unitId)?->update(['status' => 'broken']);
        }

        // ✅ Kirim notifikasi FCM
        $tokens   = FcmToken::pluck('token');
        $firebase = app(FirebaseService::class);

        foreach ($tokens as $token) {
            try {
                $response = $firebase->send(
                    $token,
                    'Permintaan Baru',
                    'Permintaan dari ' . $requestModel->requester_name,
                    url('/upt/requests')
                );

                if (in_array($response->status(), [400, 404])) {
                    $errorCode = $response->json()['error']['details'][0]['errorCode'] ?? '';
                    if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                        FcmToken::where('token', $token)->delete();
                        Log::info('Token dihapus: ' . $token);
                    }
                }
            } catch (\Exception $e) {
                Log::error('FCM error: ' . $e->getMessage());
                continue;
            }
        }

        // ✅ Kirim Filament database notifikasi
        $users = User::all();
        foreach ($users as $user) {
            Notification::make()
                ->title('Permintaan Baru')
                ->body('Permintaan dari ' . $requestModel->requester_name)
                ->icon('heroicon-o-clipboard-document-list')
                ->actions([
                    NotificationAction::make('lihat')
                        ->label('Buka')
                        ->url(route('filament.admin.resources.requests.edit', $requestModel))
                        ->markAsRead(),
                ])
                ->sendToDatabase($user, isEventDispatched: true);
        }

        return redirect()->back()->with('success', true);
    }

    public function queue()
    {
        $requests = Request::with(['category', 'location', 'infrastructure'])
            ->whereIn('status', ['Pending', 'Dikerjakan', 'Menunggu Part'])
            ->orderByRaw("FIELD(priority, 'Tinggi', 'Sedang', 'Rendah')")
            ->latest()
            ->get();

        return view('antrian', compact('requests'));
    }
}
