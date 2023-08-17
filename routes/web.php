<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    if (auth()->check()){
        return redirect('profile');
    } else {
        return redirect('login');
    }
});

Route::get('logout', function () {
    auth()->logout();
    return redirect('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])
        ->name('profile');
    Route::get('/operations', [ProfileController::class, 'operations'])
        ->name('operations');
    Route::get('/operations/table', [ProfileController::class, 'tableOperations'])
        ->name('operations.table');
});


require __DIR__ . '/auth.php';
