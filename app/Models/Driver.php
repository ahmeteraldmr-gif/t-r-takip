<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trucks(): HasMany
    {
        return $this->hasMany(Truck::class);
    }

    public function tripsCountThisMonth(): int
    {
        return Trip::whereHas('truck', fn ($q) => $q->where('driver_id', $this->id))
            ->whereMonth('departure_date', now()->month)
            ->whereYear('departure_date', now()->year)
            ->count();
    }
}
