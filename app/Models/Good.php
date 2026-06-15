<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = [
        'code',
        'goods_category_id',
        'goods_type_id',
        'procurement_item_id',
        'supplier_id',
        'name',
        'brand',
        'specification',
        'unit',
        'quantity',
        'stock',
        'price',
        'purchase_date',
        'is_consumable',
        'note',
        'photo',
        'procurement_year',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price'         => 'decimal:2',
        'is_consumable' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(GoodsCategory::class, 'goods_category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function procurementItem()
    {
        return $this->belongsTo(ProcurementItem::class);
    }

    public function allocations()
    {
        return $this->hasMany(GoodAllocation::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ✅ Generate kode inventaris otomatis
    public static function generateCode(GoodsCategory $category, Location $location, int $year, int $sequence): string
    {
        return sprintf(
            '%s%s-%s-%02d-%02d',
            $category->code,
            substr($year, 2), // 2 digit tahun
            strtoupper(str_replace(' ', '', $location->name)),
            $year % 100,
            $sequence
        );
    }

    // ✅ Total nilai barang
    public function getTotalValueAttribute(): float
    {
        return $this->quantity * ($this->price ?? 0);
    }

    public function goodsType()
    {
        return $this->belongsTo(GoodsType::class, 'goods_type_id');
    }

    public function goodUnits()
    {
        return $this->hasMany(GoodUnit::class);
    }
}
