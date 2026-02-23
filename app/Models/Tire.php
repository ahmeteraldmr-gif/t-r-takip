<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tire extends Model
{
    use HasFactory;

    protected $fillable = ['truck_id', 'position', 'change_km', 'change_date', 'brand', 'notes'];

    protected function casts(): array
    {
        return ['change_date' => 'date'];
    }

    public const POSITION_LABELS = [
        'on_sol' => 'Ön Sol',
        'on_sag' => 'Ön Sağ',
        'arka_1' => 'Arka 1',
        'arka_2' => 'Arka 2',
        'arka_3' => 'Arka 3',
        'arka_4' => 'Arka 4',
        'yedek' => 'Yedek',
        'diger' => 'Diğer',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }
}
