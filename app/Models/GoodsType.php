<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    protected $fillable = [
        'goods_category_id',
        'code',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(GoodsCategory::class, 'goods_category_id');
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }
}
