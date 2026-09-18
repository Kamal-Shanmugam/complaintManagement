<?php

use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/complaints');
Route::resource('complaints', ComplaintController::class)->only(['index', 'store', 'update', 'destroy']);
