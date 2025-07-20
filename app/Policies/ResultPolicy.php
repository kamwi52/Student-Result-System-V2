<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function update(User $user, Result $result): bool
    {
        // Your `results` table has a `teacher_id` column.
        // This is the simplest and most direct way to authorize the action.
        // We check if the logged-in user's ID matches the teacher_id on the result.
        return $user->id === $result->teacher_id;
    }
}