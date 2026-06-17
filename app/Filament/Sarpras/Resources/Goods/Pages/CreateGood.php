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

class CreateGood extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = GoodResource::class;
    protected string $view = 'filament.sarpras.resources.goods.pages.create-good';
    protected static ?string $title = 'Tambah Barang';

    public ?array $data = [];

    public function mount(): void
    {
        $typeId = request('type_id');
        $defaultData = [];

        if ($typeId) {
            $type = \App\Models\GoodsType::with('category')->find($typeId);
            if ($type) {
                $defaultData = [
                    'goods_category_id' => $type->goods_category_id,
                    'goods_type_id'     => $type->id,
                    'code'              => $type->code,
                    'name'              => $type->name,
                ];
            }
        }

        $this->form->fill($defaultData);
    }

    public function form(Schema $schema): Schema
    {
        return GoodForm::configure($schema)->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (($data['unit'] ?? '') === 'lainnya' && !empty($data['unit_custom'])) {
            $data['unit'] = $data['unit_custom'];
        }
        unset($data['unit_custom']);

        $good = Good::create($data);

        Notification::make()
            ->title('Barang berhasil ditambahkan!')
            ->success()
            ->send();

        // ✅ Redirect ke layer 2 kalau ada type_id, kalau tidak ke layer 1
        $typeId = $good->goods_type_id ?? request('type_id');

        $this->redirect(
            $typeId
                ? route('sarpras.goods.by-type', $typeId)
                : route('filament.sarpras.resources.goods.index')
        );
    }

    public function getBackUrl(): string
    {
        $typeId = request('type_id') ?? $this->data['goods_type_id'] ?? null;
        return $typeId
            ? route('sarpras.goods.by-type', $typeId)
            : route('filament.sarpras.resources.goods.index');
    }
}
