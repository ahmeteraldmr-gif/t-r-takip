<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const ROLE_PATRON = 'patron';
    public const ROLE_SOFOR = 'sofor';

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isPatron(): bool
    {
        return ($this->role ?? self::ROLE_PATRON) === self::ROLE_PATRON;
    }

    public function isSofor(): bool
    {
        return ($this->role ?? self::ROLE_PATRON) === self::ROLE_SOFOR;
    }

    /** Şoför ise kendisine bağlı Driver kaydı */
    public function driverProfile(): ?Driver
    {
        return $this->drivers()->first();
    }

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if ($user->first_name || $user->last_name) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /** Patron: kendi tırları. Şoför: atandığı tırlar */
    public function accessibleTruckIds(): \Illuminate\Support\Collection
    {
        if ($this->isPatron()) {
            return $this->trucks()->pluck('id');
        }
        $driver = $this->driverProfile();
        if (!$driver) {
            return collect();
        }
        return Truck::where('driver_id', $driver->id)->pluck('id');
    }

    public function trucks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Truck::class);
    }

    public function drivers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
