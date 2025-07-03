<?php

namespace App\Policies;

use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassSectionPolicy
{
    /**
     * Perform pre-authorization checks.
     * This 'before' method is run before any other method in the policy.
     * If it returns true or false, the authorization check stops immediately.
     */
    public function before(User $user, string $ability): bool|null
    {
        // If the user has the 'admin' role, they can do anything.
        if ($user->role === 'admin') {
            return true;
        }

        // Return null to let the other policy methods decide.
        return null;
    }

    /**
     * Determine whether the user can view the list of class sections.
     * Only admins can see the main list. Teachers see their own list on their dashboard.
     */
    public function viewAny(User $user): bool
    {
        // This is handled by the before() method for admins.
        // Returning false here denies access to everyone else for the main index page.
        return false;
    }

    /**
     * Determine whether the user can view a specific class section's details (like the gradebook).
     * A teacher can view a class if they are assigned to it.
     */
    public function view(User $user, ClassSection $classSection): bool
    {
        // The check: Does the logged-in user's ID match the teacher's ID on the class?
        return $user->id === $classSection->user_id;
    }

    /**
     * Determine whether the user can create class sections.
     * Only admins can create classes.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the class section (e.g., save grades).
     * A teacher can update a class if they are assigned to it.
     */
    public function update(User $user, ClassSection $classSection): bool
    {
        // Same logic as viewing. They must be the assigned teacher.
        return $user->id === $classSection->user_id;
    }

    /**
     * Determine whether the user can delete the class section.
     * Only admins can do this.
     */
    public function delete(User $user, ClassSection $classSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the class section.
     */
    public function restore(User $user, ClassSection $classSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the class section.
     */
    public function forceDelete(User $user, ClassSection $classSection): bool
    {
        return false;
    }
}