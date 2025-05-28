<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy
{
    /**
     * Only admins can manage their own organizations.
     */
    public function manage(User $user, Organization $organization)
    {
        return $user->isAdmin() && $user->organizations()->where('id', $organization->id)->exists();
    }
}
