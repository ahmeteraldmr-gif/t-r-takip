<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Driver $driver): bool
    {
        return $user->id === $driver->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Driver $driver): bool
    {
        return $user->id === $driver->user_id;
    }

    public function delete(User $user, Driver $driver): bool
    {
        return $user->id === $driver->user_id;
    }
}
