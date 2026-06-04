<?php

namespace App\Filament\Resources\Requests\Pages;

use App\Filament\Resources\Requests\RequestResource;
use App\Models\InfrastructureUnit;
use App\Models\RequestUnit;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->saveRequestUnits($this->record);
    }

    protected function saveRequestUnits($record): void
    {
        // ✅ Hapus unit lama kalau ada
        $record->requestUnits()->delete();

        // ✅ Simpan unit rusak
        $brokenUnitIds = $this->data['broken_unit_ids'] ?? [];
        foreach ($brokenUnitIds as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'rusak',
            ]);

            // ✅ Update status unit
            InfrastructureUnit::find($unitId)?->update(['status' => 'broken']);
        }

        // ✅ Simpan unit diperbaiki
        $fixedUnitIds = $this->data['fixed_unit_ids'] ?? [];
        foreach ($fixedUnitIds as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'diperbaiki',
            ]);

            InfrastructureUnit::find($unitId)?->update(['status' => 'good']);
        }

        // ✅ Simpan unit rusak permanen
        $permanentUnitIds = $this->data['permanent_unit_ids'] ?? [];
        foreach ($permanentUnitIds as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'permanen',
            ]);

            InfrastructureUnit::find($unitId)?->update(['status' => 'permanent_broken']);
        }
    }
}
