<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cartItems()->with('product')->get();
        return CartResource::collection($cart);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $request->user()->cartItems()->firstOrNew([
            'product_id' => $validated['product_id'],
        ]);

        $cartItem->quantity = $cartItem->exists ? $cartItem->quantity + $validated['quantity'] : $validated['quantity'];
        $cartItem->save();

        return new CartResource($cartItem->load('product'));
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update($validated);

        return new CartResource($cart->load('product'));
    }

    public function remove(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request)
    {
        $request->user()->cartItems()->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}
