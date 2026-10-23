<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Returns extends Model
{
    protected $fillable = [
        'return_code',
        'loan_id',
        'received_by',
        'return_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function returnItems(): HasMany
    {
        return $this->hasMany(ReturnItems::class, 'return_id');
    }
}