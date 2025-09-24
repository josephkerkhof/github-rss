<?php

declare(strict_types=1);

namespace App\Filament\Resources\PullRequests\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PullRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('repository.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('author.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('url')
                    ->searchable(),
                TextColumn::make('merged_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
