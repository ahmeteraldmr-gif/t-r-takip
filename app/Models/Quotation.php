<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'customer_id', 'title', 'amount', 'origin', 'destination',
        'cargo_type', 'load_weight', 'valid_until', 'status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'load_weight' => 'decimal:2',
            'valid_until' => 'date',
        ];
    }

    public const STATUS_LABELS = [
        'taslak' => 'Taslak',
        'gonderildi' => 'Gönderildi',
        'onaylandi' => 'Onaylandı',
        'reddedildi' => 'Reddedildi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
