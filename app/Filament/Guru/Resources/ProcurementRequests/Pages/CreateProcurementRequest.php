<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Pages;

use App\Filament\Guru\Resources\ProcurementRequests\ProcurementRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcurementRequest extends CreateRecord
{
    protected static string $resource = ProcurementRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ✅ Auto isi user_id dengan user yang login
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status']  = 'Draft';
        return $data;
    }
}
