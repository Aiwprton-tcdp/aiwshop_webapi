<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $data = Cart::join('goods', 'goods.id', 'good_id')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->select('goods.*')
            ->get();
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function store(CartRequest $request)
    {
        $user_id = \Illuminate\Support\Facades\Auth::id();
        $good_id = (int)request('good_id');
        $data = [
            'user_id' => $user_id,
            'good_id' => $good_id,
        ];
        $cart = Cart::create($data);
        
        return response()->json([
            "data" => $cart
        ]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('good_id', $id)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();
        $result = $cart->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
