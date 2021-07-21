<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateCartItems;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use phpDocumentor\Reflection\PseudoTypes\True_;

class CartItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute 是必要的',
            'between' => ':attribute 的輸入 :input 不在 :min 和 :max 之間'
        ];
        $Validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|between:1,10',            
            'cart_id' => 'required|integer',
            'product_id' => 'required|integer',
        ], $message);
        if($Validator->fails()){
            return response($Validator->errors(), 400);
        }
        $validateData = $Validator->validate();

        $product = Product::find($validateData['product_id']);
        if(!$product->checkQuantity($validateData['quantity'])){
            return response($product->title.'數量不足', 400);
        }

        $cart = Cart::find($validateData['cart_id']);
        $result = $cart->cartItems()->create(['product_id' => $product->id,
                                            'quantity' => $validateData['quantity']]);

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartItems $request, $id)
    {
        $form = $request->validated();
        $items = CartItem::find($id);
        // $items->update(['quantity' => $form['quantity']]);
        $items->fill(['quantity' => $form['quantity']]);
        $items->save();
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CartItem::find($id)->delete();
        return response()->json(true);
    }
}
