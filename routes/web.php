<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use \App\Models\Delivery;
use \App\Http\Controllers\DeliveryController;
use \App\Http\Controllers\ClientController;
use \App\Http\Controllers\DeliverymanController;
use \App\Http\Controllers\PointController;

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/test', function() use ($router) {
    $faker = Faker\Factory::create('pt_BR');
    $faker->seed(1234);
    //$x = new Faker();
    return response()->json([
        'address' => $faker->address(),
        'address2' => $faker->address(),
        'name' => $faker->name(),
    ]);
    //return Delivery::getStatusArray();
});

Route::get('/deliveries', [DeliveryController::class, 'index']);
Route::get('/deliveries/{id}', [DeliveryController::class, 'show']);
Route::post('/deliveries', [DeliveryController::class, 'store'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);
Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/clients', [ClientController::class, 'index']);

Route::get('/points', [PointController::class, 'index']);

*/
