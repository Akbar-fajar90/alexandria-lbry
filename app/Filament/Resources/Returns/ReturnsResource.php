<?php

namespace App\Filament\Resources\Returns;

use App\Filament\Resources\Returns\Pages\CreateReturns;
use App\Filament\Resources\Returns\Pages\EditReturns;
use App\Filament\Resources\Returns\Pages\ListReturns;
use App\Filament\Resources\Returns\Schemas\ReturnsForm;
use App\Filament\Resources\Returns\Tables\ReturnsTable;
use App\Models\Returns;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;
use Illuminate\Support\Facades\Auth;

class ReturnsResource extends Resource
{
    protected static ?string $model = Returns::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role === 'siswa') {
            $query->whereHas('loan.member', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        }

        return $query; 
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'Returns';

    public static function form(Schema $schema): Schema
    {
        return ReturnsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReturnsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ReturnItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReturns::route('/'),
            'create' => CreateReturns::route('/create'),
            'edit' => EditReturns::route('/{record}/edit'),
        ];
    }
}
