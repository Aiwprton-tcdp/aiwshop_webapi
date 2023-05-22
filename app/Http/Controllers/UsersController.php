<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $n = request('name');
        $name = strtolower(trim($n));
        $limit = (int)request('limit');

        $data = DB::table('users')
            ->whereRaw('LOWER(users.name) LIKE ? ', ["%{$name}%"])
            ->join('roles', 'roles.id', 'users.role_id')
            ->select('users.id', 'users.name', 'role_id', 'users.created_at', 'roles.name AS rolename')
            ->orderBy('users.id')
            ->paginate($limit < 1 ? 10 : $limit);
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function store()
    {
        $user = User::create(request()->validated());
        return response()->json([
            "data" => $user
        ]);
    }

    public function show($id)
    {
        return response()->json([
            "data" => $this->show($id)
        ]);
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        $user->fill(request()->except(['id']));
        $user->save();

        return response()->json([
            "data" => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $result = $user->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
