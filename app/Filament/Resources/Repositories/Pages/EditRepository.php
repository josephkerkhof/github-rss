<?php

declare(strict_types=1);

namespace App\Filament\Resources\Repositories\Pages;

use App\Filament\Resources\Repositories\RepositoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRepository extends EditRecord
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
