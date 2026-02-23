<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Trip $trip): bool
    {
        if ($user->id === $trip->truck->user_id) {
            return true;
        }
        $driver = $user->driverProfile();
        return $driver && $trip->truck->driver_id === $driver->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Trip $trip): bool
    {
        if ($user->id === $trip->truck->user_id) {
            return true;
        }
        $driver = $user->driverProfile();
        return $driver && $trip->truck->driver_id === $driver->id;
    }

    public function delete(User $user, Trip $trip): bool
    {
        return $user->id === $trip->truck->user_id;
    }
}
