<?php

use App\Console\Commands\FetchPullRequests;

Schedule::command(FetchPullRequests::class)
    ->withoutOverlapping()
    ->everyFifteenMinutes();
