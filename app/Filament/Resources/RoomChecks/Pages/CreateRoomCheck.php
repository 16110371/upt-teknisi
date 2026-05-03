<?php

namespace App\Filament\Resources\RoomChecks\Pages;

use App\Filament\Resources\RoomChecks\RoomCheckResource;
use App\Filament\Resources\RoomChecks\Schemas\RoomCheckForm;
use App\Models\Infrastructure;
use App\Models\Request;
use App\Models\RoomCheck;
use App\Models\RoomCheckItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CreateRoomCheck extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = RoomCheckResource::class;
    protected string $view = 'filament.resources.room-checks.pages.create-room-check';
    protected static ?string $title = 'Buat Pengecekan Baru';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return RoomCheckForm::configure($schema)->statePath('data'); // ✅ pakai RoomCheckForm
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        if (empty($data['location_id'])) {
            Notification::make()
                ->title('Pilih lokasi terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        if (empty($data['items'])) {
            Notification::make()
                ->title('Tidak ada item untuk dicek')
                ->warning()
                ->send();
            return;
        }

        $roomCheck = RoomCheck::create([
            'location_id' => $data['location_id'],
            'user_id'     => Auth::id(),
            'note'        => $data['general_note'] ?? '',
        ]);

        foreach ($data['items'] as $item) {
            $quantity = (int) ($item['quantity'] ?? 1);

            RoomCheckItem::create([
                'room_check_id'     => $roomCheck->id,
                'infrastructure_id' => $item['infrastructure_id'],
                'status'            => $item['status'],
                'quantity'          => $item['status'] === 'Bermasalah' ? $quantity : 0,
                'note'              => $item['note'] ?? '',
            ]);

            if ($item['status'] === 'Bermasalah') {
                $infra = Infrastructure::find($item['infrastructure_id']);

                $infra->update([
                    'good'   => max(0, $infra->good - $quantity),
                    'broken' => $infra->broken + $quantity,
                ]);

                Request::create([
                    'request_date'      => now(),
                    'requester_name'    => Auth::user()->name,
                    'category_id'       => $infra->category_id,
                    'location_id'       => $data['location_id'],
                    'infrastructure_id' => $item['infrastructure_id'],
                    'damaged_quantity'  => $quantity,
                    'from_room_check'   => true, // ✅ tandai dari pengecekan
                    'description'       => 'Ditemukan masalah saat pengecekan ruang: ' . ($item['note'] ?: 'Tidak ada keterangan'),
                    'status'            => 'Pending',
                    'priority'          => 'Sedang',
                ]);
            }
        }

        Notification::make()
            ->title('Pengecekan berhasil disimpan!')
            ->success()
            ->send();

        $this->redirect(RoomCheckResource::getUrl('index'));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
