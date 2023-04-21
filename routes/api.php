<?php
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UsersSocialController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    

Route::group(['middleware' => 'guest'], function() {
  Route::get('goods', [GoodsController::class, 'index']);
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/forgot-password', [AuthController::class, 'forgot_password']);
  Route::post('/password-reset', [AuthController::class, 'password_reset']);
});

Route::group(['middleware' => 'auth:sanctum'], function() {
  Route::get('/current', [AuthController::class, 'current']);
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::get('/{id}/profile', [ProfileController::class, 'index']);
  Route::get('/profile/cart', [ProfileController::class, 'cart']);
  Route::get('/profile/socials', [ProfileController::class, 'socials']);

  Route::apiResource('sales', SaleController::class)->except([
    'update', 'destroy'
  ]);
  Route::apiResource('carts', CartController::class)->except([
    'show', 'update'
  ]);
  Route::apiResource('goods', GoodsController::class)->except([
    'index'
  ]);

  Route::apiResources([
    'users_socials' => UsersSocialController::class,
  ]);

  Route::group(['middleware' => ['moderator']], function () {
    Route::apiResources([
      'users' => UsersController::class,
      'roles' => RolesController::class,
      'sales' => SaleController::class,
      // 'socials' => SocialController::class,
    ]);
    
    Route::apiResource('socials', SocialController::class)->except([
      'index'
    ]);

    Route::get('report', [GoodsController::class, 'report']);
    Route::get('send-mail', [MailController::class, 'index']);
    
    Route::get('chart/roles', [ChartController::class, 'roles']);
    Route::get('chart/prices', [ChartController::class, 'prices']);
    Route::get('chart/sales', [ChartController::class, 'sales']);
    
    Route::group(['middleware' => ['admin']], function () {
      Route::post('login_to_another', [AuthController::class, 'login_to_another']);
    });
  });
});
