<?php

declare(strict_types=1);

namespace App\Filament\Resources\PullRequests\Pages;

use App\Filament\Resources\PullRequests\PullRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListPullRequests extends ListRecords
{
    protected static string $resource = PullRequestResource::class;
}
