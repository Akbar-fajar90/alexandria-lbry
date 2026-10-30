<?php
// app/Filament/Resources/Loans/Schemas/LoansForm.php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LoansForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = Auth::user()?->role === 'admin';

        return $schema
            ->components([
                TextInput::make('loan_code')
                    ->label('Kode Peminjaman')
                    ->disabled()
                    ->dehydrated(),
                Select::make('member_id')
                    ->label('Anggota')
                    ->relationship('member', 'member_code')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->member_code} - {$record->user->name}")
                    ->required()
                    ->default(fn () => !$isAdmin ? Auth::user()?->member?->id : null)
                    ->disabled(!$isAdmin)
                    ->dehydrated(),

                TextInput::make('processed_by')
                    ->label('Diproses Oleh (Admin ID)')
                    ->default(fn () => $isAdmin ? Auth::id() : null)
                    ->hidden(!$isAdmin),

                DatePicker::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->default(now())
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('due_date', $state 
                            ? Carbon::parse($state)->addDays(7)->format('Y-m-d') 
                            : null
                        );
                    }),

                DatePicker::make('due_date')
                    ->label('Tenggat Waktu (7 Hari)')
                    ->default(fn () => now()->addDays(7))
                    ->required()
                    ->readOnly(),

                Select::make('status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'borrowed' => 'Dipinjam',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('pending')
                    ->disabled(!$isAdmin) 
                    ->dehydrated()
                    ->required(),

                Repeater::make('LoanDetails')
                    ->relationship() 
                    ->label('Buku Yang Dipinjam')
                    ->schema([
                        Select::make('book_id')
                            ->label('Buku')
                            ->relationship('book', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->addActionLabel('Tambah Buku Lain'),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }
}