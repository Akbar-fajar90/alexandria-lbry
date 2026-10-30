<?php
// app/Filament/Resources/Returns/Tables/ReturnsTable.php

namespace App\Filament\Resources\Returns\Tables;

use App\Models\Returns;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_code')
                    ->label('Kode Pengembalian')
                    ->searchable(),
                TextColumn::make('loan.loan_code')
                    ->label('Kode Pinjam')
                    ->searchable(),
                TextColumn::make('loan.member.user.name')
                    ->label('Nama Peminjam')
                    ->searchable(),
                TextColumn::make('return_date')
                    ->label('Tgl Kembali')
                    ->date()
                    ->sortable(),
                TextColumn::make('received_by')
                    ->label('Status / Diterima')
                    ->formatStateUsing(fn ($state, Returns $record) => $state ? $record->receiver->name : 'Pending (Menunggu Audit)')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => Auth::user()?->role === 'admin'),

                Action::make('confirm')
                    ->label('Konfirmasi Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Pastikan kondisi buku dan denda sudah diaudit sebelum menyelesaikan pengembalian ini.')
                    ->visible(fn (Returns $record) => Auth::user()?->role === 'admin' && is_null($record->received_by))
                    ->action(function (Returns $record) {
                        $record->update([
                            'received_by' => Auth::id(),
                        ]);
                        $record->loan->update([
                            'status' => 'returned',
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