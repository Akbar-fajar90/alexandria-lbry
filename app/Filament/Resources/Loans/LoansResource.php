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

class LoansResource extends Resource
{
    protected static ?string $model = Loans::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            RelationManagers\LoanDetailsRelationManager::class,
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
