<?php

use App\Http\Controllers\AprendizController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/aprendices');

Route::resource('aprendices', AprendizController::class)
    ->parameters(['aprendices' => 'aprendiz'])
    ->except(['show']);