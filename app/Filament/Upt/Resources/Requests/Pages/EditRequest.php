<?php

namespace App\Filament\Upt\Resources\Requests\Pages;

use App\Filament\Upt\Resources\Requests\RequestResource;
use App\Models\GoodUnit;
use App\Models\RequestUnit;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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
        $record = $this->record;

        // ✅ Kembalikan status unit lama
        foreach ($record->requestUnits as $ru) {
            $unit = GoodUnit::find($ru->unit_id);
            if (!$unit) continue;
            if ($ru->type === 'rusak') $unit->update(['status' => 'good']);
            if ($ru->type === 'permanen') $unit->update(['status' => 'broken']);
        }

        $record->requestUnits()->delete();

        // ✅ Simpan unit baru
        foreach ($this->data['broken_unit_ids'] ?? [] as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'rusak',
            ]);
            GoodUnit::find($unitId)?->update(['status' => 'broken']);
        }

        foreach ($this->data['fixed_unit_ids'] ?? [] as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'diperbaiki',
            ]);
            GoodUnit::find($unitId)?->update(['status' => 'good']);
        }

        foreach ($this->data['permanent_unit_ids'] ?? [] as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'permanen',
            ]);
            GoodUnit::find($unitId)?->update(['status' => 'permanent_broken']);
        }
    }
}
