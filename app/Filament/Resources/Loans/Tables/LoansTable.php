<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\Loans;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_code')
                    ->label('Kode Pinjam')
                    ->searchable(),
                TextColumn::make('member.user.name')
                    ->label('Nama Peminjam')
                    ->searchable(),
                TextColumn::make('loanDetails.book.title')
                    ->label('Buku yang Dipinjam')
                    ->listWithLineBreaks()
                    ->limitList(2) 
                    ->expandableLimitedList(),
                TextColumn::make('loan_date')
                    ->label('Tgl Pinjam')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Konfirmasi',
                        'borrowed' => 'Dipinjam',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'borrowed' => 'info',
                        'returned' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (Loans $record) => $record->status === 'pending'),

                Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Loans $record) => Auth::user()?->role === 'admin' && $record->status === 'pending')
                    ->form([
                        Select::make('status')
                            ->label('Ubah Status')
                            ->options([
                                'borrowed' => 'Setujui (Dipinjam)',
                                'cancelled' => 'Tolak (Batal)',
                            ])
                            ->required()
                            ->default('borrowed'),
                    ])
                    ->action(function (Loans $record, array $data) {
                        $record->update([
                            'status' => $data['status'],
                            'processed_by' => Auth::id(),
                        ]);
                    }),

                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Loans $record) => 
                        $record->status === 'pending' && 
                        (Auth::user()?->role === 'admin' || Auth::id() === $record->member?->user_id)
                    )
                    ->action(function (Loans $record) {
                        $record->update([
                            'status' => 'cancelled',
                        ]);
                    }),

                    ActivityLogTimelineTableAction::make('Activities')
                ->withRelations(['profile', 'address'])
                ->timelineIcons([
                    'created' => 'heroicon-m-check-badge',
                    'updated' => 'heroicon-m-pencil-square',
                ])
                ->timelineIconColors([
                    'created' => 'info',
                    'updated' => 'warning',
                ])
                ->limit(10),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}