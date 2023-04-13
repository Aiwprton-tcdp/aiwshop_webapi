<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function index()
    {
        $n = request('name');
        $name = strtolower(trim($n));
        $limit = request('limit') ?? 100;
        $offset = request('offset') ?? 0;

        $data = DB::table('roles')
            ->whereRaw('LOWER(roles.name) LIKE ? ', ["%{$name}%"])
            ->select('roles.*')
            ->skip($offset)->limit($limit)
            ->orderBy('roles.id')
            ->get();
        
        return response()->json([
            "data" => $data->toArray()
        ]);
    }

    public function store()
    {
        $user = Role::create(request()->validated());
        return response()->json([
            "data" => $user
        ]);
    }

    public function show($id)
    {
        $data = Role::select('users.*', 'roles.name AS rolename')
            ->first($id);
        
        return response()->json([
            "data" => $data
        ]);
    }

    public function update($id)
    {
        $user = Role::findOrFail($id);
        $user->fill(request()->except(['id']));
        $user->save();

        return response()->json([
            "data" => $user
        ]);
    }

    public function destroy($id)
    {
        $user = Role::findOrFail($id);
        $result = $user->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
