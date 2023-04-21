<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function roles()
    {
        $data = DB::table('users')
            ->join('roles', 'roles.id', 'users.role_id')
            ->selectRaw('roles.name AS rolename, COUNT(users.id) AS count')
            ->groupBy('roles.id')
            ->get();
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function prices()
    {
        $data = DB::table('goods')
            ->selectRaw('price, COUNT(id) AS count')
            ->groupBy('price')
            ->get();
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function sales()
    {
        $data = DB::table('sales')
            ->whereIsReturned(false)
            ->selectRaw('DATE(updated_at) AS date, COUNT(id) AS count, AVG(price) AS avg_price')
            ->groupBy('updated_at')
            ->orderBy('updated_at')
            ->get();
        
        return response()->json([
            "data" => $data
        ]);
    }
}
