<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'date',
        'liters',
        'price_per_liter',
        'total_amount',
        'liters_used',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'liters' => 'decimal:2',
            'price_per_liter' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'liters_used' => 'decimal:2',
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
