<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddlewate;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('adminLogin', [AuthController::class, 'adminLogin']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::prefix('user')->group(function () {
    Route::get('/', function () {
        return 'cc';
    });
    Route::get('/{id}', [UserController::class, 'index'])->middleware(JWTMiddlewate::class);
    Route::post('/register', [UserController::class, 'register']);
    Route::patch('/address', [UserController::class, 'createAddress']);
    Route::delete('/address/{index}', [UserController::class, 'deleteAddress']);
    // Route::post('/login', [UserController::class, 'login']);
    // Route::post('/logout', 'App\Http\Controllers\UserController@logout');
});

Route::prefix('admin')->group(function () {
    Route::get('/{id}', [UserController::class, 'index']);
});

Route::prefix('/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'get']);
    Route::post('/', [CategoryController::class, 'create'])->middleware('JWTMiddlewate');
    Route::patch('/{id}', [CategoryController::class, 'update'])->middleware(JWTMiddlewate::class);
    Route::delete('/{id}', [CategoryController::class, 'delete'])->middleware('JWTMiddlewate');
});

Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/name', [ProductController::class, 'getProductByCategoryName']);
    Route::get('/{id}', [ProductController::class, 'get']);
    Route::get('/category/{id}', [ProductController::class, 'getProductByCategoryId']);
    Route::post("/", [ProductController::class, 'create'])->middleware('JWTMiddlewate');
    Route::post('/{id}', [ProductController::class, 'update'])->middleware('JWTMiddlewate');
    Route::delete('/{id}', [ProductController::class, 'delete'])->middleware('JWTMiddlewate');
    Route::get('/price/{id}', [ProductController::class, 'getPrice']);
});

Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::get('/{id}', [CartController::class, 'getByUserId']);
    Route::get('/user/count', [CartController::class, 'count']);
    Route::post('/', [CartController::class, 'create']);
    Route::patch('/decrease/{id}', [CartController::class, 'decrease']);
    Route::patch('/increase/{id}', [CartController::class, 'increase']);
    Route::delete('/{id}', [CartController::class, 'delete']);
});
