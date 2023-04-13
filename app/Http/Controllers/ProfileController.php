<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Social;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index($id)
    {
        $id = (int)$id == 0 ? Auth::id() : (int)$id;
        // var_dump((int)$id, Auth::id());
        // if (&& Auth::id() != $id)

        $data = DB::table('users')
            ->join('roles', 'roles.id', 'users.role_id')
            ->where('users.id', $id)
            ->select('users.id', 'users.name', 'users.created_at', 'roles.name AS role')
            ->first();
        // $data = User::with(['role:id,name'])
        //     ->exclude(['password', 'remember_token'])
        //     ->where('users.id', $id)
        //     ->first();
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function cart()
    {
        $data = DB::table('goods')
            ->join('carts', 'carts.good_id', 'goods.id')
            ->whereUserId(Auth::id())
            ->get();
        // $data = DB::table('goods')->join('carts', 'carts.good_id', 'goods.id')
        //     ->where('carts.user_id', Auth::id())
        //     ->select('goods.id', 'goods.name', 'goods.price')
        //     ->get();
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function socials()
    {
        $data = Social::with(['users_data'])->get();

        return response()->json([
            "data" => $data
        ]);
    }
}
