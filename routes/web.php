<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;


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


Route::get('/', [NavController::class, 'home']);
Route::get('/list', [NavController::class, 'list']);
Route::get('/book/{id}', [NavController::class, 'book']);
Route::get('/add', [NavController::class, 'add']);
Route::post('/addBook', [BookController::class, 'add']);
Route::post('/deleteBook', [BookController::class, 'delete']);

Route::get('/updateBook/{id}', [NavController::class, 'updateBook']);
Route::post('/updateBook', [BookController::class, 'update']);
