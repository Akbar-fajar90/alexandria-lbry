<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Malzariey\FilamentDaterangepickerFilter;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class BooksForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->unique('categories', 'name')
                    ])
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('author')
                    ->required(),
                TextInput::make('publisher'),
                DateRangePicker::make('publication_year')
                    ->label('Tahun Terbit')
                    ->yearPicker()
                    ->format('Y')
                    ->dehydrateStateUsing(function ($state) {
                        return (int) explode(' - ', $state)[0];
                    }),
                TextInput::make('isbn'),
                FileUpload::make('image')
                    ->label('Cover Buku')
                    ->image()
                    ->imageEditor()
                    ->directory('books')
                    ->disk('public')
                    ->maxSize(2048),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shelf_location'),
            ]);
    }
}
