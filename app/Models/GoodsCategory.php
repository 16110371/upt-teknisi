<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function goods()
    {
        return $this->hasMany(Good::class);
    }

    public function goodsTypes()
    {
        return $this->hasMany(GoodsType::class);
    }
}
