<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart_goods = Cart::join('goods', 'goods.id', 'good_id')
            ->whereUserId(Auth::id())
            ->get();
        
        return response()->json([
            "data" => $cart_goods
        ]);
    }

    public function store(CartRequest $request)
    {
        $good_id = (int)request('good_id');

        if ($good_id < 1) {
            return response()->json([
                "data" => null,
                "error" => 'incorrect \'good_id\' parameter',
                "status" => false,
            ]);
        }

        $data = [
            'user_id' => Auth::id(),
            'good_id' => $good_id,
        ];
        $cart_good = Cart::create($data);
        
        return response()->json([
            "data" => $cart_good
        ]);
    }

    public function destroy($id)
    {
        $cart_good = Cart::whereGoodId($id)
            ->whereUserId(Auth::id())
            ->firstOrFail();
        $result = $cart_good->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
