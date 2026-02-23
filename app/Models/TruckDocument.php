<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TruckDocument extends Model
{
    use HasFactory;

    protected $fillable = ['truck_id', 'type', 'path', 'original_name', 'expiry_date', 'notes'];

    protected function casts(): array
    {
        return ['expiry_date' => 'date'];
    }

    public const TYPE_LABELS = [
        'muayene' => 'Muayene',
        'sigorta' => 'Sigorta',
        'kasko' => 'Kasko',
        'ruhsat' => 'Ruhsat',
        'diger' => 'Diğer',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
