<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'location',
        'stopped_at',
        'left_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'stopped_at' => 'datetime',
            'left_at' => 'datetime',
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
