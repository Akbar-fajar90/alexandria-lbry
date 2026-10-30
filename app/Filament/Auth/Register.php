<?php
// app/Filament/Auth/Register.php

namespace App\Filament\Auth;   

use App\Models\Members;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),

                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(20)
                    ->unique(Members::class, 'nis'),

                TextInput::make('class_name')
                    ->label('Kelas')
                    ->required()
                    ->maxLength(50),

                TextInput::make('major')
                    ->label('Jurusan')
                    ->maxLength(100),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $memberFields = ['nis', 'class_name', 'major'];

            $user = parent::handleRegistration(
                collect($data)->except($memberFields)->toArray()
            );

            Members::create([
                'user_id'     => $user->id,
                'member_code' => 'AGT-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                'joined_at'   => now(),
                ...collect($data)->only($memberFields)->toArray(),
            ]);

            return $user;
        });
    }

    protected function getRedirectUrl(): string
    {
        return \App\Filament\Resources\Loans\LoansResource::getUrl('index');
    }
}