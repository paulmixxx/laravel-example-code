<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PanelPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->isAdmin();
    }
}
