<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', [ContactController::class, 'index']);
Route::post('/contacts/store', [ContactController::class, 'store'])->name('contacts.store');
