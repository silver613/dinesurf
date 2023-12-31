<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRegistrationLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRegistrationLogPolicy
{
    use HandlesAuthorization;

    public $access = false;

    public function __construct()
    {
        $user = auth()->user();
        if ($user) {
            $this->access = $user->is_admin ?: session('admin_logged_in');
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->access;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRegistrationLog  $userRegistrationLog
     * @return mixed
     */
    public function view(User $user, UserRegistrationLog $userRegistrationLog)
    {
        return $this->access;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRegistrationLog  $userRegistrationLog
     * @return mixed
     */
    public function update(User $user, UserRegistrationLog $userRegistrationLog)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRegistrationLog  $userRegistrationLog
     * @return mixed
     */
    public function delete(User $user, UserRegistrationLog $userRegistrationLog)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRegistrationLog  $userRegistrationLog
     * @return mixed
     */
    public function restore(User $user, UserRegistrationLog $userRegistrationLog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRegistrationLog  $userRegistrationLog
     * @return mixed
     */
    public function forceDelete(User $user, UserRegistrationLog $userRegistrationLog)
    {
        //
    }
}
