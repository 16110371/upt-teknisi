<?php

namespace App\Filament\Upt\Resources\Requests\Pages;

use App\Filament\Upt\Resources\Requests\RequestResource;
use App\Models\GoodUnit;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ✅ Ambil category_id dari unit pertama yang dipilih
        $brokenUnitIds = $this->data['broken_unit_ids'] ?? [];

        if (!empty($brokenUnitIds)) {
            $unit = \App\Models\GoodUnit::with('good.category')->find($brokenUnitIds[0]);
            $data['category_id'] = $unit?->good?->goods_category_id ?? null;
        }

        // ✅ Kalau tidak ada unit, set category_id nullable
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        return $data;
    }

    protected function saveRequestUnits($record): void
    {
        $record->requestUnits()->delete();

        // ✅ Unit rusak
        foreach ($this->data['broken_unit_ids'] ?? [] as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'rusak',
            ]);
            GoodUnit::find($unitId)?->update(['status' => 'broken']);
        }

        // ✅ Unit diperbaiki
        foreach ($this->data['fixed_unit_ids'] ?? [] as $unitId) {
            RequestUnit::create([
                'request_id' => $record->id,
                'unit_id'    => $unitId,
                'type'       => 'diperbaiki',
            ]);
            GoodUnit::find($unitId)?->update(['status' => 'good']);
        }

        // ✅ Unit rusak permanen
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
