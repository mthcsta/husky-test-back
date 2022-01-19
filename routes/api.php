<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\DeliverymanController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome',
        'version' => '1.0'
    ]);
});
/*
Route::get('/clients', function() {
    return new App\Http\Resources\ClientResource(App\Models\Client::find(10));
});

*/

Route::get('/deliveries', [DeliveryController::class, 'index']);
Route::get('/delivery/{id}', [DeliveryController::class, 'show']);
Route::post('/delivery', [DeliveryController::class, 'store']);
Route::put('/delivery/{id}', [DeliveryController::class, 'update']);
Route::delete('/delivery/{id}', [DeliveryController::class, 'destroy']);

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/client/{id}', [ClientController::class, 'show']);
Route::post('/client', [ClientController::class, 'store']);

Route::get('/points', [PointController::class, 'index']);
Route::post('/point', [PointController::class, 'store']);

Route::get('/deliverymen', [DeliverymanController::class, 'index']);
Route::post('/deliveryman', [DeliverymanController::class, 'store']);
Route::get('/deliverymen-nearby/{lat}/{long}', [DeliverymanController::class, 'showNearby']);
