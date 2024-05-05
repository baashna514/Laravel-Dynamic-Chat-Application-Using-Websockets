<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    return view('welcome');
});

Route::get('/dashboard', [UserController::class, 'loadDashboard'])->middleware(['auth'])->name('dashboard');
Route::post('/save-chat', [UserController::class, 'saveChat'])->middleware(['auth'])->name('save-chat');
Route::get('/load-chat', [UserController::class, 'loadChat'])->middleware(['auth'])->name('load-chat');
Route::get('/delete-chat', [UserController::class, 'deleteChat'])->middleware(['auth'])->name('delete-chat');

require __DIR__.'/auth.php';
