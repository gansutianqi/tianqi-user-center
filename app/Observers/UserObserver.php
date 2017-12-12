<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Deleting
     */
    public function deleting(User $user)
    {
        // delete avatar image in the disk when a user is deleting
        $path = $user->profile->avatar_url;
        Storage::disk('public')->delete($path);
    }
}


