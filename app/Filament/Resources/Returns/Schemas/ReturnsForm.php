<?php
// app/Filament/Resources/Returns/Schemas/ReturnsForm.php

namespace App\Filament\Resources\Returns\Schemas;

use App\Models\LoanDetails;
use App\Models\Loans;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ReturnsForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = Auth::user()?->role === 'admin';

        return $schema
            ->components([
                TextInput::make('return_code')
                    ->label('Kode Pengembalian')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Select::make('loan_id')
                    ->label('Pilih Peminjaman')
                    ->relationship(
                        name: 'loan', 
                        titleAttribute: 'loan_code',
                        modifyQueryUsing: function (Builder $query) use ($isAdmin) {
                            $query->whereIn('status', ['borrowed', 'overdue']);
                            if (!$isAdmin) {
                                $query->whereHas('member', fn (Builder $q) => $q->where('user_id', Auth::id()));
                            }
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->loan_code} - {$record->member->user->name}")
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateFine($get, $set)),

                TextInput::make('received_by')
                    ->label('Diterima Oleh (Admin ID)')
                    ->default(fn () => $isAdmin ? Auth::id() : null)
                    ->hidden(!$isAdmin), 

                DatePicker::make('return_date')
                    ->label('Tanggal Pengembalian')
                    ->default(now())
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateFine($get, $set)),

                Repeater::make('returnItems')
                    ->relationship()
                    ->label('Buku yang Dikembalikan')
                    ->schema([
                        Select::make('loan_detail_id')
                            ->label('Buku')
                            ->options(function (Get $get) {
                                $loanId = $get('../../loan_id'); 
                                if (!$loanId) return [];
                                
                                return LoanDetails::where('loan_id', $loanId)
                                    ->whereDoesntHave('returnItem')
                                    ->with('book')
                                    ->get()
                                    ->pluck('book.title', 'id');
                            })
                            ->searchable()
                            ->distinct()
                            ->required()
                            ->validationMessages([
                                'distinct' => 'Buku ini sudah ditambahkan di baris lain. Silakan periksa kembali.',
                            ]),
                            
                        TextInput::make('qty_returned')
                            ->label('Jumlah Dikembalikan')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                            
                        Select::make('item_condition')
                            ->label('Kondisi Buku')
                            ->options([
                                'good' => 'Baik',
                                'damaged' => 'Rusak',
                                'lost' => 'Hilang',
                            ])
                            ->default('good')
                            ->disabled(!$isAdmin) 
                            ->dehydrated()
                            ->required(),
                            
                        TextInput::make('fine')
                            ->label('Denda (Rp)')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->disabled(!$isAdmin) 
                            ->dehydrated(),
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

    private static function calculateFine(Get $get, Set $set): void
    {
        $loanId = $get('loan_id');
        $returnDate = $get('return_date');
        
        if (!$loanId || !$returnDate) return;
        
        $loan = Loans::find($loanId);
        if (!$loan) return;

        $dueDate = Carbon::parse($loan->due_date);
        $returnDateParsed = Carbon::parse($returnDate);
        
        $daysLate = 0;
        if ($returnDateParsed->greaterThan($dueDate)) {
            $daysLate = (int) $returnDateParsed->diffInDays($dueDate);
        }
        
        $fineAmount = $daysLate * 1000;

        $items = $get('returnItems') ?? [];
        foreach ($items as $key => $item) {
            $items[$key]['fine'] = $fineAmount;
        }
        $set('returnItems', $items);
    }
}