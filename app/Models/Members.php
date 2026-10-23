<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Members extends Model
{
    protected $fillable = [
        'user_id',
        'member_code',
        'nis',
        'class_name',
        'major',
        'phone',
        'address',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loans::class, 'member_id');
    }
}