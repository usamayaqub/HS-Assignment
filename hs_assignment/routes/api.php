<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userctrl;
use App\Http\Controllers\filectrl;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\OrderController;

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

// %%%%%%%%%%%%%%%%%%%% Public API's %%%%%%%%%%%%%%%%%%

    Route::post('/register', [userctrl::class, 'register']);
    Route::post('/login', [userctrl::class, 'login']);
    Route::post('password/email',  [ForgotPasswordController::class, 'forgot']);
    Route::post('password/code/check', [CodeCheckController::class, 'codecheck']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::get('/test',[userctrl::class, 'test']);

// %%%%%%%%%%%%%%%%%%%% Public API's %%%%%%%%%%%%%%%%%%%%%
      
// %%%%%%%%%%%%%%%%%%%% Privatye/Sanctum Secured API's %%%%%%%%%%%%%%%%%%%%

    Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [userctrl::class, 'logout']);
    Route::post('/update/{id}', [userctrl::class, 'update']); 
    
    Route::get('/show', [userctrl::class, 'showusers']);
    Route::post('/addfile', [filectrl::class, 'store']);
    Route::post('/updatefile/{id}', [filectrl::class, 'update']);
    Route::post('/deletefile/{id}', [filectrl::class, 'destroy']);
    Route::post('/placeorder', [orderController::class, 'addorder']);
    Route::post('/updateorder/{id}', [orderController::class, 'updateorder']);
    Route::post('/deleteorder/{id}', [orderController::class, 'deleteorder']);
    Route::get('/showorders', [orderController::class, 'showorders']);

});

// %%%%%%%%%%%%%%% Privatye/Sanctum Secured API's %%%%%%%%%%%%%%%%