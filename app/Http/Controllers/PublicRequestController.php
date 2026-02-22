<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request as HttpRequest;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Services\ImageService;




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
            'description' => 'required|string',
            'priority' => 'nullable|string|in:Rendah,Sedang,Tinggi',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:8192', // max 8MB
        ]);

        if (!isset($validated['priority'])) {
            $validated['priority'] = 'Rendah';
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $validated['photo'] = ImageService::compress(
                $request->file('photo'),
                'requests'
            );
        }

        $validated['status'] = 'Pending';
        $validated['request_date'] = $validated['request_date'] ?? now();

        $requestModel = Request::create($validated);

        $users = User::all();

        foreach ($users as $user) {
            Notification::make()
                ->title('Permintaan Baru')
                ->body('Permintaan dari ' . $requestModel->requester_name)
                ->icon('heroicon-o-clipboard-document-list')
                ->actions([
                    Action::make('lihat')
                        ->label('Buka')
                        ->url(route('filament.admin.resources.requests.edit', $requestModel))
                        ->markAsRead(),
                ])
                ->sendToDatabase($user);
        }


        return redirect()->route('public-request.create')->with('success', 'Permintaan berhasil dikirim!');
    }
}
