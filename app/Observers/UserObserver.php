<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Create a profile after create a user
     * @param User $user
     */
    public function created(User $user)
    {
        $user->profile()->create([]);
    }

    /**
     * Delete profile  after delete a user
     * @param User $user
     */
    public function deleted(User $user)
    {
        $user->profile()->delete();
    }
}


