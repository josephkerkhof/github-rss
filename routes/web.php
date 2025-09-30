<?php

declare(strict_types=1);

use App\Http\Controllers\RenderRssFeedController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/repos/{owner}/{repo}/pulls/merged.rss', RenderRssFeedController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
