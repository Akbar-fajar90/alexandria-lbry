<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Members extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }
    
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