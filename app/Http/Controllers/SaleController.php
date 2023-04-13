<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index()
    {
        $good = request('good');
        $buyer = request('buyer');
        $min_price = request('min_price');
        $max_price = request('max_price');
        $limit = (int)request('limit');
        $is_returned = request('is_returned');

        $data = DB::table('sales')
            ->where(function ($query) use($good) {
                if (isset($good) && is_numeric($good)) {
                    $g = (int)$good;
                    if ($g < 1) $g = 1;
                    $query->where('good', $g);
                }
            })
            ->where(function ($query) use($buyer) {
                if (!in_array(Auth::user()->role_id, [2, 3])) {
                    $query->where('buyer', Auth::id());
                }
                elseif (isset($buyer) && is_numeric($buyer)) {
                    $b = (int)$buyer;
                    $query->where('buyer', $b < 1 ? Auth::id() : $b);
                }
            })
            ->where(function ($query) use($min_price, $max_price) {
                $is_min = isset($min_price);
                $is_max = !empty($max_price);
                $min = (int)$min_price;
                $max = (int)$max_price;

                if ($min < 0) $min = 0;
                if ($max < $min) $max = $min;

                if ($is_min && $is_max) {
                    $query->whereBetween('price', [$min, $max]);
                } elseif ($is_min) {
                    $query->where('price', '>=', $min);
                } elseif ($is_max) {
                    $query->where('price', '<=', $max);
                }
            })
            ->where(function ($query) use($is_returned) {
                if (isset($is_returned)) {
                    $query->where('is_returned', (bool)$is_returned);
                }
            })
            ->paginate($limit < 1 ? 50 : $limit);
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function store(SaleRequest $request)
    {
        $sale = Sale::create($request->validated());
        return response()->json([
            "data" => $sale
        ]);
    }

    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        return response()->json([
            "data" => $sale
        ]);
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->fill($request->except(['id']));
        $sale->save();

        Log::info("Sale #" + $id + " has been successfully updated");

        return response()->json([
            "data" => $sale
        ]);
    }

    // public function destroy($id)
    // {
    //     // $good = null;
    //     // try {
    //     //     $good = Good::findOrFail($id);
    //     // } catch (ModelNotFoundException $e) {
    //     //     return false;
    //     // } finally {
    //     //     // return $good?->delete();
    //     //     return false;
    //     // }

    //     $sale = Sale::findOrFail($id);
    //     $result = $sale->delete();

    //     return response()->json([
    //         "data" => $result
    //     ]);
    // }
}
