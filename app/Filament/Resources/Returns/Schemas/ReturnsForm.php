<?php

namespace App\Filament\Resources\Returns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReturnsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('return_code')
                    ->required(),
                Select::make('loan_id')
                    ->relationship('loan', 'id')
                    ->required(),
                TextInput::make('received_by')
                    ->numeric(),
                DatePicker::make('return_date')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
