<?php

namespace App\Http\Controllers;

use App\Exports\GoodsExport;
use App\Models\Good;
use App\Http\Requests\GoodRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller
{
    public function index()
    {
        // if (Cache::store('file')->has('public_goods')) {
        //     $value = Cache::store('file')->get('public_goods');
        //     return response()->json([
        //         "data" => $value
        //     ]);
        // }
        $is_file = boolval(request('is_file'));

        $n = request('search');
        $cart_ids = request('cart_ids');

        $name = mb_strtolower(trim($n));
        $min_price = request('min_price');
        $max_price = request('max_price');
        $limit = (int)request('limit');

        if ($is_file) {
            $data = DB::table('goods')
                ->where(function($query) use($cart_ids) {
                    if (!empty($cart_ids)) {
                        $query->whereIn('id', array_map('intval', explode(',', $cart_ids)));
                    }
                })
                ->where(function($query) use($name, $n, $min_price, $max_price) {
                    if (empty($cart_ids)) {
                        $query->whereRaw('LOWER(name) LIKE ? OR id = ?', ["%{$name}%", (int)$n])
                            ->whereBetween('price', [$min_price, $max_price]);
                    }
                })
                ->get();
    
            // var_dump($data, isset($data));
            return $this->report($data);
        }

        $data = DB::table('goods')
            ->when(!empty($cart_ids), function($q) use($cart_ids) {
                $q->whereIn('id', array_map('intval', explode(',', $cart_ids)));
            })
            ->when(empty($cart_ids), function($q) use($name, $n, $min_price, $max_price) {
                $q->whereRaw('LOWER(name) LIKE ? OR id = ?', ["%{$name}%", (int)$n])
                    ->whereBetween('price', [$min_price, $max_price]);
            })
            // ->where(function($query) use($cart_ids) {
            //     if (!empty($cart_ids)) {
            //         $query->whereIn('id', array_map('intval', explode(',', $cart_ids)));
            //     }
            // })
            // ->where(function($query) use($name, $n, $min_price, $max_price) {
            //     if (empty($cart_ids)) {
            //         $query->whereRaw('LOWER(name) LIKE ? OR id = ?', ["%{$name}%", (int)$n])
            //             ->whereBetween('price', [$min_price, $max_price]);
            //     }
            // })
            ->where(function($query) use($min_price, $max_price) {
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
            ->paginate($limit < 1 ? 10 : $limit);

        // Cache::store('file')->put('public_goods', $data, 300);
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function report($d)
    { // https://laravel-excel.com
        $now = \Carbon\Carbon::now()->timestamp;
        $name = 'GoodsReport-'.$now.'.xlsx';
        // $path = storage_path('app\\public\\' . $name);
        // Excel::store(new GoodsExport, $name, 'public');
        $data = isset($d) ? $d : new GoodsExport;
        return Excel::download($data, $name);
        // return response()->download($path, $name);
    }

    public function store(GoodRequest $request)
    {
        $good = Good::create($request->validated());
        return response()->json([
            "data" => $good
        ]);
    }

    public function show($id)
    {
        $good = Good::findOrFail($id);
        return response()->json([
            "data" => $good
        ]);
    }

    public function update(Request $request, $id)
    {
        $good = Good::findOrFail($id);
        dd($good);
        return response()->json([
            "data" => $good
        ]);
        $good->fill($request->except(['id']));
        $good->save();

        Log::info("Good #" + $id + " has been successfully updated");

        return response()->json([
            "data" => $good
        ]);
    }

    public function destroy($id)
    {
        $good = Good::findOrFail($id);
        $result = $good->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
