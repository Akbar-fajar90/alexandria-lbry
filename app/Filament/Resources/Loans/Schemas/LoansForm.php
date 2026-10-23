<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LoansForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('loan_code')
                    ->required(),
                Select::make('member_id')
                    ->relationship('member', 'id')
                    ->required(),
                TextInput::make('processed_by')
                    ->numeric(),
                DatePicker::make('loan_date')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                Select::make('status')
                    ->options([
            'borrowed' => 'Borrowed',
            'returned' => 'Returned',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
        ])
                    ->default('borrowed')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
