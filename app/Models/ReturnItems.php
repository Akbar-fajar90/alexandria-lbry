<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ReturnItems extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }
    
    protected $fillable = [
        'return_id',
        'loan_detail_id',
        'qty_returned',
        'item_condition',
        'fine',
    ];

    protected function casts(): array
    {
        return [
            'qty_returned' => 'integer',
            'fine' => 'decimal:2',
        ];
    }

    public function return(): BelongsTo
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    public function loanDetail(): BelongsTo
    {
        return $this->belongsTo(LoanDetails::class, 'loan_detail_id');
    }
}
