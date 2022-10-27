<?php

namespace Modules\Users\Interfaces;

interface UserRepositoryInterface
{
    /**
     * Assign a role to the given user.
     * @return mixed
     */
    public function assignRole($user, $role);
}
