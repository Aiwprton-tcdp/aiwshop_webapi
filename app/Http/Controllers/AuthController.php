<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Models\PasswordReset;
use App\Models\UsersSocial;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\WebSocketTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use UserTrait, WebSocketTrait;

    /** 
     * Login The User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse 
     */
    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'login' => 'string|required',
            'password' => 'string|required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        
        // if (!empty($request->name) && !Auth::attempt([
        //     'name' => request('name'),
        //     'password' => request('password')
        // ])) {
        //     return response()->json([
        //         'status' => false,
        //         'data' => null,
        //         'message' => 'The provided credentials are incorrect.',
        //     ], 401);
        // }

        $user = User::join('users_socials', 'users_socials.user_id', 'users.id')
            ->whereName(request('login'))
            ->OrWhere('users_socials.value', request('login'))
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'data' => $user->createToken(env('JWT_SECRET', "auth"))->plainTextToken
        ], 200);
    }
    
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse  
     */
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'string|required',
            'social' => 'string|required',
            'password' => 'string|required'
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        // var_dump($request->password, Hash::make($request->password));

        try {
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password)
            ]);
            UsersSocial::create([
                'user_id' => $user->id,
                'social_id' => 1,
                'value' => $request->social
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => $user->createToken(env('JWT_SECRET', "auth"))->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function current()
    {
        return response()->json([
            "data" => $this->show(Auth::id())
        ]);
    }
    
    public function logout(Request $request)
    {
        // Auth::logout();
        // $request->user()->currentAccessToken()->delete();
        // return response();

        if (method_exists($request->user()->currentAccessToken(), 'delete')) {
            $request->user()->currentAccessToken()->delete();
        }
        
        // $request->guard('web')->logout();

        return response()->json([
            "data" => true
        ]);
    }

    public function forgot_password(Request $request)
    {
        if (!isset($request->social)) {
            return response()->json([
                "data" => null
            ]);
        }

        $users_social_data = DB::table('users_socials')
            ->where('value', $request->social) 
            ->select('social_id')->first();

        $tokenData = PasswordReset::create([
            'users_social_id' => $users_social_data->social_id,
            'token' => Str::upper(Str::random(6)),
        ]);
        
        return response()->json([
            "data" => $tokenData->token,
        ]);
    }
    
    public function password_reset(Request $request)
    {
        if (!isset($request->token) || !isset($request->password)) {
            return response()->json([
                'status' => false,
                'message' => "No data",
            ]);
        }

        $is_actual_token = DB::table('password_resets')
            ->join('users_socials', 'users_socials.id', 'users_social_id')
            ->where('token', $request->token)
            ->select('password_resets.id', 'users_socials.user_id')
            ->orderByDesc('id')
            ->first();

        if (!isset($is_actual_token->id)) {
            return response()->json([
                'status' => false,
                'message' => "Incorrect token",
            ]);
        }

        $user = User::whereId($is_actual_token->user_id)->first();
        $user->password = Hash::make($request->password);
        $user->update(); //or $user->save();
        
        return response()->json([
            'status' => true,
            "data" => "The password has been successfully changed",
        ]);
    }

    public function login_to_another()
    {
        $user = User::findOrFail(request('id'));

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'data' => $user->createToken(env('JWT_SECRET', "auth"))->plainTextToken
        ], 200);
    }
    
}
