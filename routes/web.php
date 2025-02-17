<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
//use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');

Route::resource('contacts', ContactController::class); // ONLY ONCE

Route::get('/import-contacts', function () {
    return view('contacts.import');
})->name('contacts.import.form');

Route::post('/import-contacts', [ContactController::class, 'import'])->name('contacts.import');

// Route::post('/test-csrf', [TestController::class, 'testCsrf']);
