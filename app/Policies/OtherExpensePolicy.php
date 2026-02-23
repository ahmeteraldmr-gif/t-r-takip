<?php

namespace App\Policies;

use App\Models\OtherExpense;
use App\Models\User;

class OtherExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OtherExpense $otherExpense): bool
    {
        return $user->id === $otherExpense->trip->truck->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, OtherExpense $otherExpense): bool
    {
        return $user->id === $otherExpense->trip->truck->user_id;
    }

    public function delete(User $user, OtherExpense $otherExpense): bool
    {
        return $user->id === $otherExpense->trip->truck->user_id;
    }
}
