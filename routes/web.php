<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

// Show contact list page
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

// Store new contact
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
