<?php

declare(strict_types=1);

namespace App\Filament\Resources\PullRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PullRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('repository.name')
                    ->numeric(),
                TextEntry::make('branch.name')
                    ->numeric(),
                TextEntry::make('author.id')
                    ->numeric(),
                TextEntry::make('number'),
                TextEntry::make('title'),
                TextEntry::make('url'),
                TextEntry::make('merged_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
