<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LoanDetails extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }
    
    protected $fillable = [
        'loan_id',
        'book_id',
        'qty',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Books::class, 'book_id');
    }

    public function returnItem(): HasOne
    {
        return $this->hasOne(ReturnItems::class, 'loan_detail_id');
    }
}
