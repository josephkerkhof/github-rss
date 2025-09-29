<?php

declare(strict_types=1);

namespace App\Filament\Resources\PullRequests;

use Override;
use App\Filament\Resources\PullRequests\Pages\ListPullRequests;
use App\Filament\Resources\PullRequests\Pages\ViewPullRequest;
use App\Filament\Resources\PullRequests\Schemas\PullRequestInfolist;
use App\Filament\Resources\PullRequests\Tables\PullRequestsTable;
use App\Models\PullRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PullRequestResource extends Resource
{
    protected static ?string $model = PullRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return PullRequestInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return PullRequestsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPullRequests::route('/'),
            'view' => ViewPullRequest::route('/{record}'),
        ];
    }
}
