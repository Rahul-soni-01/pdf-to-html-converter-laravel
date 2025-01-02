<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/upload-pdf', function () {
    return view('upload-pdf');
})->name('upload.pdf');

Route::post('/upload-pdf', [PdfController::class, 'store'])->name('pdf.store');

