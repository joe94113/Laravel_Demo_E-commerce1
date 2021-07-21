<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [''];

    private $rate = 1; // 費率

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function checkout()
    {
        foreach($this->cartItems as $cartItem){
            $product = $cartItem->product;
            if(!$product->checkQuantity($cartItem->quantity)) { 
                return $product->title.'數量不足'; 
            }
        }

        $order = $this->order()->create([
            'user_id' => $this->user_id
        ]);

        if ($this->user->level == 2) { // 如果用戶等級為2
            $this->rate = 0.8; // 費率變成0.8
        }

        foreach($this->cartItems as $cartItem){
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'price' => $cartItem->product->price * $this->rate
            ]);
            $cartItem->product->update(['quantity' => $cartItem->product->quantity - $cartItem->quantity]);
        }
        $this->update(['checkouted' => true]);
        return $order;
    }
}