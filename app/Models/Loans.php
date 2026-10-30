<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Loans extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }
    
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

    protected static function booted(): void
    {
        static::creating(function (Loans $loan) {
            if (empty($loan->loan_code)) {
                $date = date('Ymd');
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $loan->loan_code = 'LOAN-' . $date . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
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