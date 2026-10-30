<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Returns extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }
    
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

     protected static function booted(): void
    {
        static::creating(function (Returns $returns) {
            if (empty($returns->return_code)) {
                $date = date('Ymd');
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $returns->return_code = 'RET-' . $date . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
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