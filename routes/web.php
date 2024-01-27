<?php

use App\Http\Controllers\TenantController;
use App\Models\Tenant;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tenant', [TenantController::class, 'create']);
Route::post('/tenant/store', [TenantController::class, 'store']);
// Route::get('/tenant', function () {
//     $tenant = new Tenant();
//     $tenant->name = "localhost";
//     $tenant->subdomain = "localhost";
//     $tenant->save();
// });


Route::middleware(['tenancy'])->group(function () {
    Route::get('/{subdomain}/dashboard', 'DashboardController@index');
    // Other tenant-specific routes
});
