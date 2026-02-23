<?php

namespace App\Policies;

use App\Models\FuelExpense;
use App\Models\User;

class FuelExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FuelExpense $fuelExpense): bool
    {
        return $user->id === $fuelExpense->trip->truck->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FuelExpense $fuelExpense): bool
    {
        return $user->id === $fuelExpense->trip->truck->user_id;
    }

    public function delete(User $user, FuelExpense $fuelExpense): bool
    {
        return $user->id === $fuelExpense->trip->truck->user_id;
    }
}
