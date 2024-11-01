<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

    
Route::get('/search-users', [UserController::class, 'searchUsers'])->name('search.users');
Route::post('/users/{userId}', [UserController::class, 'toggleFollow'])->name('toggle.follow');