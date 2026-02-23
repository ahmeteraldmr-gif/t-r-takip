<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'type',
        'due_date',
        'last_done_date',
        'last_done_km',
        'notes',
        'cost',
    ];

    public function scopeForUser(Builder $query, \App\Models\User $user): Builder
    {
        $ids = $user->accessibleTruckIds();
        return $ids->isEmpty() ? $query->whereRaw('0=1') : $query->whereIn('truck_id', $ids);
    }

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'last_done_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public const TYPE_LABELS = [
        'yağ_değişimi' => 'Yağ Değişimi',
        'fren' => 'Fren Bakımı',
        'muayene' => 'Muayene',
        'sigorta' => 'Sigorta',
        'kasko' => 'Kasko',
        'lastik' => 'Lastik',
        'diğer' => 'Diğer',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date->isPast() && !$this->last_done_date;
    }

    public function getDaysUntilDueAttribute(): ?int
    {
        return now()->diffInDays($this->due_date, false);
    }
}
