<?php

namespace App\Filament\Resources\Books;

use App\Filament\Resources\Books\Pages\CreateBooks;
use App\Filament\Resources\Books\Pages\EditBooks;
use App\Filament\Resources\Books\Pages\ListBooks;
use App\Filament\Resources\Books\Schemas\BooksForm;
use App\Filament\Resources\Books\Tables\BooksTable;
use App\Models\Books;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BooksResource extends Resource
{
    protected static ?string $model = Books::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Books';

    public static function form(Schema $schema): Schema
    {
        return BooksForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BooksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
            'create' => CreateBooks::route('/create'),
            'edit' => EditBooks::route('/{record}/edit'),
        ];
    }
}
