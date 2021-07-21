<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;  // 使用軟刪除特性

    protected $filable = []; // 白名單(只能更改)
    protected $guarded = []; // 黑名單(不能更改)
    protected $hidden = []; //隱藏欄位 
    protected $appends = ['current_price'];

    public function getCurrentPriceAttribute()
    {
        return $this->quantity * 10;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
