<?php

declare(strict_types=1);

use App\Domains\Repositories\Http\Controllers\BranchController;
use App\Domains\Repositories\Http\Controllers\RepositoryController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(RepositoryController::class)->group(function (): void {
        Route::get('/repositories', 'index');
    });

    Route::controller(BranchController::class)
        ->prefix('{repository:uuid}')
        ->group(function (): void {
            Route::get('/branches', 'index');
        });
});


if (app()->isLocal()) {
    Route::post('/dummy-login', function (Request $request) {
        $user = User::find(1);

        $tokenName = 'dev-token';
        $user->tokens()->where('name', $tokenName)->delete();

        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    });
}
