<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $this->admin($user) || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $this->admin($user) || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $this->admin($user) || $user->id === $model->id;
    }

    /**
     * Determine whether a user is admin
     * @param User $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->email === '20654039@qq.com';
    }

    /**
     * Determine whether the user can edit their avatar
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function editAvatar(User $user, User $model)
    {
        return $user->id === $model->id;
    }

}
