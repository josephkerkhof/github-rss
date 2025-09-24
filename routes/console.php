<?php

declare(strict_types=1);

use App\Console\Commands\FetchPullRequests;

Schedule::command(FetchPullRequests::class)
    ->withoutOverlapping()
    ->everyFifteenMinutes();
