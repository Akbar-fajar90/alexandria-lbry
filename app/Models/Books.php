<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Books extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() 
            ->logOnlyDirty(); 
    }

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'image',
        'stock',
        'shelf_location',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetails::class, 'book_id');
    }
}
