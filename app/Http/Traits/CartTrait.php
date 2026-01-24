<?php
namespace App\Http\Traits;
use App\Models\Cart;

trait CartTrait
{

    //adds to session
    protected function addToCartDb($meal_id,$food_id,$size_id,$price,$quantity = 1){
        $cart = Cart::create([
            'ip' => request()->ip(),
            'meal_id' => $meal_id,
            'food_id' => $food_id,
            'size_id' => $size_id,
            'price' => $price,
            'quantity' => $quantity,
            'amount' => $price * $quantity
        ]);
        return true;
    }
    
    protected function removeFromCartDb($meal_id = null)
    {
        if ($meal_id) {
            $cart = Cart::where('ip', request()->ip())->where('meal_id', $meal_id)->delete();
        } else {
            // Clear all cart items for current IP
            $cart = Cart::where('ip', request()->ip())->delete();
        }
        return true;
    }

    protected function getCartDb(){
        $carts = Cart::where('ip',request()->ip())->get();
        return $carts;
    }

    protected function getCartDbByMealId($meal_id){
        $carts = Cart::where('ip',request()->ip())->where('meal_id',$meal_id)->get();
        return $carts;
    }

    protected function removeCartItemDb($cartItemId)
    {
        $cart = Cart::where('ip', request()->ip())->where('id', $cartItemId)->delete();
        return true;
    }

    protected function updateCartItemQuantityDb($cartItemId, $quantity)
    {
        $cart = Cart::where('ip', request()->ip())->where('id', $cartItemId)->first();
        if ($cart) {
            $cart->update([
                'quantity' => $quantity,
                'amount' => $cart->price * $quantity
            ]);
        }
        return true;
    }

}


