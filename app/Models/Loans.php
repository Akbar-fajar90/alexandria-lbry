<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Loans extends Model
{
    protected $fillable = [
        'loan_code',
        'member_id',
        'processed_by',
        'loan_date',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetails::class, 'loan_id');
    }

    public function return(): HasOne
    {
        return $this->hasOne(Returns::class, 'loan_id');
    }
}