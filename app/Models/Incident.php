<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'date',
        'type',
        'description',
        'cost',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
