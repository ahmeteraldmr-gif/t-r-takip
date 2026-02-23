<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plate',
        'ruhsat_no',
        'brand',
        'model',
        'driver_id',
        'driver_name',
        'status',
    ];

    public const STATUS_LABELS = [
        'aktif' => 'Aktif',
        'bakımda' => 'Bakımda',
        'satıldı' => 'Satıldı',
        'kiralık' => 'Kiralık',
        'devre_dışı' => 'Devre Dışı',
    ];

    public function scopeForUser(Builder $query, \App\Models\User $user): Builder
    {
        $ids = $user->accessibleTruckIds();
        return $ids->isEmpty() ? $query->whereRaw('0=1') : $query->whereIn('id', $ids);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TruckDocument::class, 'truck_id');
    }

    public function tires(): HasMany
    {
        return $this->hasMany(Tire::class);
    }

    public function getTotalKmAttribute(): int
    {
        $lastTrip = $this->trips()->whereNotNull('end_km')->orderByDesc('ended_at')->first();
        return $lastTrip ? (int) $lastTrip->end_km : 0;
    }

    public function getDriverDisplayNameAttribute(): ?string
    {
        return $this->driver?->name ?? $this->driver_name;
    }
}
