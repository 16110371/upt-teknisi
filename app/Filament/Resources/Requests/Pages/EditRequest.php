<?php

namespace App\Filament\Resources\Requests\Pages;

use App\Filament\Resources\Requests\RequestResource;
use App\Models\InfrastructureUnit;
use App\Models\RequestUnit;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ✅ Load unit ids saat edit
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        $data['broken_unit_ids']    = $record->brokenUnits()->pluck('unit_id')->toArray();
        $data['fixed_unit_ids']     = $record->fixedUnits()->pluck('unit_id')->toArray();
        $data['permanent_unit_ids'] = $record->permanentUnits()->pluck('unit_id')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        Log::info('Data saat save:', $this->data);
        $this->saveRequestUnits($this->record);
    }

    protected function saveRequestUnits($record): void
    {
        // ✅ Kembalikan status unit lama dulu
        foreach ($record->requestUnits as $ru) {
            $unit = $ru->unit;
            if (!$unit) continue;

            if ($ru->type === 'rusak') {
                $unit->update(['status' => 'good']);
            } elseif ($ru->type === 'permanen') {
                $unit->update(['status' => 'broken']);
            }
        }

        // ✅ Hapus unit lama
        $record->requestUnits()->delete();

        // ✅ Simpan unit rusak baru
        $brokenUnitIds = $this->data['broken_unit_ids'] ?? [];
        foreach ($brokenUnitIds as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'rusak',
            ]);
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
