<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Filament\Sarpras\Resources\Goods\GoodResource;
use App\Filament\Sarpras\Resources\Goods\Schemas\GoodForm;
use App\Models\Good;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class EditGood extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = GoodResource::class;
    protected string $view = 'filament.sarpras.resources.goods.pages.edit-good';
    protected static ?string $title = 'Edit Barang';

    public Good $good;
    public ?array $data = [];

    public function mount(int $id): void
    {
        $this->good = Good::findOrFail($id);

        $presets = ['pcs', 'unit', 'set', 'box', 'dus', 'rim', 'lusin', 'pack', 'roll', 'botol', 'buah', 'meter', 'lembar'];

        $data = $this->good->toArray();

        // ✅ Handle unit custom
        if (!in_array($data['unit'], $presets)) {
            $data['unit_custom'] = $data['unit'];
            $data['unit']        = 'lainnya';
        }

        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return GoodForm::configure($schema)->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // ✅ Handle unit lainnya
        if (($data['unit'] ?? '') === 'lainnya' && !empty($data['unit_custom'])) {
            $data['unit'] = $data['unit_custom'];
        }
        unset($data['unit_custom']);

        $this->good->update($data);

        Notification::make()
            ->title('Barang berhasil diupdate!')
            ->success()
            ->send();

        // ✅ Redirect ke layer 2
        $this->redirect(route('sarpras.goods.by-type', $this->good->goods_type_id));
    }

    public function getBackUrl(): string
    {
        return route('sarpras.goods.by-type', $this->good->goods_type_id);
    }
}
