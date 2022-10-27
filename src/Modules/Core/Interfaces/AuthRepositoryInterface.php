<?php

namespace Modules\Core\Interfaces;

use Carbon\Carbon;
use Modules\Core\Repositories\NotActivatedException;

interface AuthRepositoryInterface
{
    /**
     * Authenticate a user
     * @param $username
     * @param $password
     * @param $remember_me
     * @return bool
     */
    public function login($username, $password, $remember_me);

    /**
     * Register a new user.
     * @param array $data
     * @return bool
     */
    public function register(array $data);

    public function assignRole($user_data, $role);

    /**
     * Log the user out of the application.
     * @return bool
     */
    public function logout(): void;

    /**
     * Activate the given used id
     * @param int $userId
     * @param string $code
     * @return mixed
     */
    public function expires(): Carbon;

    /**
     * Check if the user is logged in
     * @return mixed
     */
    public function check();

    public function removeExpired(): bool;

    public function createActivation($user): string;

    public function generateCode(): string;

    public function createPasswordReset($email);

    public function completeResetPassword($code, $password);

    /**
     * @param $user
     * @return bool
     * @throws NotActivatedException
     * Check if user account has been verified
     */
    public function checkActivation($user): bool;

    /**
     * Login with ID
     * @return mixed
     */
    public function loginWithId($id);

    /**
     * Login with User instance
     * @return mixed
     */
    public function loginWithUserInstance($user);
}
