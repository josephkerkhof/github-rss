<?php

declare(strict_types=1);

namespace App\Filament\Resources\Repositories\Pages;

use App\Filament\Resources\Repositories\RepositoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRepository extends CreateRecord
{
    protected static string $resource = RepositoryResource::class;
}
