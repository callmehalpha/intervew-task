<?php

namespace Modules\Users\Repositories;

use Modules\Core\Repositories\CoreRepository;
use Modules\Users\Entities\User;
use Modules\Users\Interfaces\UserRepositoryInterface;

class UserRepository extends CoreRepository implements  UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Assign a role to the given user.
     * @return mixed
     */

    public function assignRole($user, $role)
    {
        return $user->assignRole($role);
    }
}


