<?php

declare(strict_types=1);

use App\Domains\Repositories\Http\Controllers\RepositoryController;
use App\Http\Controllers\RenderRssFeedController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('repositories', static function () {
        $repositories = auth()->user()->repositories->map(fn ($repository) => [
            'uuid' => $repository->uuid,
            'name' => $repository->name,
            'slug' => sprintf('%s/%s', $repository->owner, $repository->repo),
            'owner' => $repository->owner,
            'repo' => $repository->repo,
        ]);

        return Inertia::render('Repositories', [
            'repositories' => $repositories,
        ]);
    })->name('repositories');

    Route::post('repositories', [RepositoryController::class, 'store'])->name('repositories.store');
    Route::put('repositories/{repository:uuid}', [RepositoryController::class, 'update'])->name('repositories.update');
    Route::delete('repositories/{repository:uuid}', [RepositoryController::class, 'destroy'])->name('repositories.destroy');
});

Route::get('/repos/{owner}/{repo}/pulls/merged.rss', RenderRssFeedController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
