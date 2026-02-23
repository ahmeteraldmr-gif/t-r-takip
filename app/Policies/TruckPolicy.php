<?php

namespace App\Policies;

use App\Models\Truck;
use App\Models\User;

class TruckPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Truck $truck): bool
    {
        if ($user->id === $truck->user_id) {
            return true;
        }
        $driver = $user->driverProfile();
        return $driver && $truck->driver_id === $driver->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Truck $truck): bool
    {
        return $user->id === $truck->user_id;
    }

    public function delete(User $user, Truck $truck): bool
    {
        return $user->id === $truck->user_id;
    }
}
