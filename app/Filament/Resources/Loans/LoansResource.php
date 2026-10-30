<?php

namespace App\Filament\Resources\Loans;

use App\Filament\Resources\Loans\Pages\CreateLoans;
use App\Filament\Resources\Loans\Pages\EditLoans;
use App\Filament\Resources\Loans\Pages\ListLoans;
use App\Filament\Resources\Loans\Schemas\LoansForm;
use App\Filament\Resources\Loans\Tables\LoansTable;
use App\Models\Loans;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;
use Illuminate\Support\Facades\Auth;

class LoansResource extends Resource
{

    protected static ?string $model = Loans::class;

        #[Override]
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role === 'siswa') {
            $query->whereHas('member', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        }

        return $query; 
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?string $recordTitleAttribute = 'Loans';

    public static function form(Schema $schema): Schema
    {
        return LoansForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\LoanDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoans::route('/'),
            'create' => CreateLoans::route('/create'),
            'edit' => EditLoans::route('/{record}/edit'),
        ];
    }

}
