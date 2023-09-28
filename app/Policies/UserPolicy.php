<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public $access = false;

    public function __construct()
    {
        $user = auth()->user();
        if ($user) {
            $this->access = $user->is_admin ?: session('admin_logged_in');
        }
    }

    public function viewAny(User $user)
    {
        return $this->access;
    }

    public function view(User $user)
    {
        return $this->access;
    }

    public function create(User $user)
    {
        //;
    }

    public function update(User $user)
    {
        return $this->access;
    }

    public function delete(User $user)
    {
        //;
    }

    public function approve(User $user)
    {
        //;
    }

    public function disapprove(User $user)
    {
        //;
    }

    public function makeAdmin(User $user)
    {
        //;
    }

    public function sendNotification(User $user)
    {
        //;
    }
}
