<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations\Pages;

use App\Filament\Sarpras\Resources\GoodAllocations\GoodAllocationResource;
use App\Models\GoodUnit;
use Filament\Notifications\Notification;
use Filament\Actions\Action as NotifAction;
use Filament\Resources\Pages\CreateRecord;

class CreateGoodAllocation extends CreateRecord
{
    protected static string $resource = GoodAllocationResource::class;

    public bool $generateUnitCodes = true;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id']         = auth()->id();
        $this->generateUnitCodes = $data['generate_unit_codes'] ?? true;
        unset($data['generate_unit_codes']);
        return $data;
    }

    protected function afterCreate(): void
    {
        if (!$this->generateUnitCodes) {
            // ✅ Notif tanpa QR untuk barang habis pakai
            Notification::make()
                ->title('Alokasi berhasil!')
                ->success()
                ->send();
            return;
        }

        $allocation = $this->record;
        $good       = $allocation->good;
        $location   = $allocation->location;

        if (!$location->room_code) {
            Notification::make()
                ->title('Alokasi berhasil!')
                ->body('⚠️ Kode unit tidak di-generate karena lokasi belum memiliki kode ruang.')
                ->warning()
                ->send();
            return;
        }

        // ✅ Generate kode unit
        $lastSequence = GoodUnit::where('good_id', $good->id)
            ->where('location_id', $location->id)
            ->count();

        for ($i = 1; $i <= $allocation->quantity; $i++) {
            $sequence = $lastSequence + $i;
            $code     = GoodUnit::generateCode($good, $location, $sequence);

            GoodUnit::create([
                'good_id'            => $good->id,
                'good_allocation_id' => $allocation->id,
                'location_id'        => $location->id,
                'code'               => $code,
            ]);
        }

        // ✅ Notif dengan tombol cetak QR
        Notification::make()
            ->title('Alokasi berhasil!')
            ->body("{$allocation->quantity} kode unit berhasil di-generate.")
            ->success()
            ->actions([
                NotifAction::make('cetak_qr')
                    ->label('🖨️ Cetak QR')
                    ->url(route('sarpras.allocation.qr', $allocation->id))
                    ->openUrlInNewTab(),
            ])
            ->persistent()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
