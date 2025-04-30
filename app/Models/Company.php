<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // 商品とのリレーション（1対多）
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
