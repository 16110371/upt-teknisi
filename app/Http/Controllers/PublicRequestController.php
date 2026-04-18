<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Category;
use App\Models\FcmToken;
use App\Models\Location;
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
            'locations' => Location::all(),
        ]);
    }

    public function store(HttpRequest $request)
    {

        $validated = $request->validate([
            'request_date' => 'nullable|date',
            'requester_name' => 'required|string|max:100',
            'requester_contact' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'infrastructure_id'  => 'nullable|exists:infrastructures,id',
            'damaged_quantity'   => 'nullable|integer|min:1',
            'description' => 'required|string',
            'priority' => 'nullable|string|in:Rendah,Sedang,Tinggi',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $validated['photo'] = ImageService::compress(
                $request->file('photo'),
                'requests'
            );
        }

        $validated['priority'] = $validated['priority'] ?? 'Rendah';
        $validated['status'] = 'Pending';
        $validated['request_date'] = $validated['request_date'] ?? now();
        $validated['damaged_quantity'] = $validated['damaged_quantity'] ?? 1;

        $requestModel = Request::create($validated);

        $tokens = FcmToken::pluck('token');

        $firebase = app(\App\Services\FirebaseService::class);

        foreach ($tokens as $token) {
            try {
                $response = $firebase->send(
                    $token,
                    'Permintaan Baru',
                    'Permintaan dari ' . $requestModel->requester_name,
                    url('/admin/requests')
                );

                // Hapus token tidak valid otomatis
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
        $requests = Request::with(['category', 'location', 'infrastructure', 'technician'])
            ->whereIn('status', ['Pending', 'Dikerjakan', 'Menunggu Part'])
            ->orderByRaw("FIELD(priority, 'Tinggi', 'Sedang', 'Rendah')")
            ->latest()
            ->get();


        return view('antrian', compact('requests'));
    }
}
