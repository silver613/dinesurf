<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoucherUsageLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherUsageLogPolicy
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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->access;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherUsageLog  $voucherUsageLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, VoucherUsageLog $voucherUsageLog)
    {
        return $this->access;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherUsageLog  $voucherUsageLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, VoucherUsageLog $voucherUsageLog)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherUsageLog  $voucherUsageLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, VoucherUsageLog $voucherUsageLog)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherUsageLog  $voucherUsageLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, VoucherUsageLog $voucherUsageLog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VoucherUsageLog  $voucherUsageLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, VoucherUsageLog $voucherUsageLog)
    {
        //
    }
}
