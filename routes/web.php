<?php

use App\Http\Controllers\RenderRssFeedController;

Route::get('/repos/{owner}/{repo}/pulls/merged.rss', RenderRssFeedController::class);
