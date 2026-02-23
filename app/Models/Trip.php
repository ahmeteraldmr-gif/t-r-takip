<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'customer_id',
        'departure_date',
        'origin',
        'destination',
        'stopovers',
        'status',
        'started_at',
        'ended_at',
        'commission_amount',
        'revenue_amount',
        'payment_status',
        'notes',
        'days_stayed',
        'start_km',
        'end_km',
        'cargo_type',
        'load_weight',
        'loading_date',
        'unloading_date',
        'receiver_name',
    ];

    public function scopeForUser(Builder $query, \App\Models\User $user): Builder
    {
        $ids = $user->accessibleTruckIds();
        return $ids->isEmpty() ? $query->whereRaw('0=1') : $query->whereIn('truck_id', $ids);
    }

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'commission_amount' => 'decimal:2',
            'revenue_amount' => 'decimal:2',
            'stopovers' => 'array',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'loading_date' => 'date',
            'unloading_date' => 'date',
            'load_weight' => 'decimal:2',
        ];
    }

    public const CARGO_TYPE_LABELS = [
        'kuru_yük' => 'Kuru Yük',
        'soğuk_zinciri' => 'Soğuk Zinciri',
        'sıvı' => 'Sıvı',
        'konteyner' => 'Konteyner',
        'inşaat' => 'İnşaat',
        'tehlikeli' => 'Tehlikeli Madde',
        'diğer' => 'Diğer',
    ];

    public function getTotalKmAttribute(): ?int
    {
        if ($this->start_km !== null && $this->end_km !== null && $this->end_km >= $this->start_km) {
            return $this->end_km - $this->start_km;
        }
        return null;
    }

    public function getDurationSecondsAttribute(): ?int
    {
        $start = $this->started_at;
        $end = $this->ended_at ?? now();
        if (!$start) {
            return null;
        }
        return (int) $end->diffInSeconds($start);
    }

    public function getDurationDisplayAttribute(): ?string
    {
        $seconds = $this->duration_seconds;
        if ($seconds === null) {
            return null;
        }
        $days = (int) floor($seconds / 86400);
        $hours = (int) floor(($seconds % 86400) / 3600);
        $minutes = (int) floor(($seconds % 3600) / 60);
        $parts = [];
        if ($days > 0) {
            $parts[] = $days . ' gün';
        }
        if ($hours > 0 || $days > 0) {
            $parts[] = $hours . ' saat';
        }
        if ($minutes > 0 || $hours > 0 || $days > 0) {
            $parts[] = $minutes . ' dk';
        }
        return $parts ? implode(' ', $parts) : '1 dk altı';
    }

    public function getRouteDisplayAttribute(): string
    {
        $parts = array_filter([$this->origin, ...($this->stopovers ?? []), $this->destination]);
        return implode(' → ', $parts) ?: ($this->destination ?? '-');
    }

    public const PAYMENT_STATUS_LABELS = [
        'bekliyor' => 'Bekliyor',
        'tahsil_edildi' => 'Tahsil Edildi',
        'kismi' => 'Kısmi',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function fuelExpenses(): HasMany
    {
        return $this->hasMany(FuelExpense::class);
    }

    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function tripStops(): HasMany
    {
        return $this->hasMany(TripStop::class);
    }

    public function getTotalFuelExpenseAttribute(): float
    {
        return $this->fuelExpenses->sum('total_amount');
    }

    public function getTotalLitersAttribute(): float
    {
        return (float) $this->fuelExpenses->sum('liters');
    }

    public function getTotalLitersUsedAttribute(): float
    {
        return (float) $this->fuelExpenses->sum('liters_used');
    }

    public function getIncidentsByTypeAttribute(): array
    {
        return $this->incidents->groupBy('type')->map->count()->all();
    }

    public function getTotalOtherExpenseAttribute(): float
    {
        return $this->otherExpenses->sum('amount');
    }

    public function getTotalIncidentCostAttribute(): float
    {
        return $this->incidents->sum('cost');
    }

    public function getTotalExpenseAttribute(): float
    {
        return $this->total_fuel_expense
            + $this->total_other_expense
            + $this->total_incident_cost
            + ($this->commission_amount ?? 0);
    }

    public function getProfitAttribute(): ?float
    {
        $revenue = (float) ($this->revenue_amount ?? 0);
        if ($revenue <= 0) {
            return null;
        }
        return $revenue - $this->total_expense;
    }

    public function getFuelConsumptionPer100KmAttribute(): ?float
    {
        $km = $this->total_km;
        $liters = $this->total_liters_used > 0 ? $this->total_liters_used : $this->total_liters;
        if (!$km || $km <= 0 || !$liters || $liters <= 0) {
            return null;
        }
        return round(($liters / $km) * 100, 2);
    }
}
