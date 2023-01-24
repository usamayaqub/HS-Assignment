<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userctrl;
use App\Http\Controllers\productctrl;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/product', [productctrl::class, 'index']);
Route::post('/register', [userctrl::class, 'register']);
Route::post('/login', [userctrl::class, 'login']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::post('/logout', [userctrl::class, 'logout']);
    Route::post('/product', [productctrl::class,'store']);
    Route::delete('/product/{id}', [productctrl::class,'destroy']);
    Route::post('/product/{id}', [productctrl::class,'update']);
   
});
