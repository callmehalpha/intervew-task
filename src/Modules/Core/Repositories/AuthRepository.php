<?php

namespace Modules\Core\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Core\Entities\Activated;
use Modules\Core\Entities\PasswordReset;
use Modules\Users\Interfaces\UserRepositoryInterface;

class AuthRepository implements \Modules\Core\Interfaces\AuthRepositoryInterface
{
    protected $userRepo;
    protected $activator;
    protected $password_reset;
    /**
     * The expiration time in seconds.
     *
     * @var int
     */
    protected $expires = 259200;


    public function __construct(UserRepositoryInterface $userRepository, Activated $activated, PasswordReset $password_reset)
    {
        $this->userRepo = $userRepository;
        $this->activator = $activated;
        $this->password_reset = $password_reset;
    }

    /**
     * Authenticate a user
     * @param $username
     * @param $password
     * @param $remember_me
     * @return bool
     */
    public function login($username, $password, $remember_me)
    {
        return Auth::attempt([
            'email' => $username,
            'password' => $password,
        ], $remember_me);

    }

    /**
     * Register a new user.
     * @param array $data
     * @return bool
     */
    public function register(array $data)
    {
        return $this->userRepo->create($data);
    }

    public function assignRole($user_data, $role)
    {
        return $this->userRepo->assignRole($user_data, $role);
    }

    /**
     * Log the user out of the application.
     * @return bool
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Activate the given used id
     * @param int $userId
     * @param string $code
     * @return mixed
     */
/*    public function activate($code)
    {
        $code_model = $this->activator::where('code', $code)
            ->where('completed', false)
            ->where('created_at', '>', $this->expires());

        $get_code_model = $code_model->get(['user_id']);

        $activate = $code_model->update([
            'completed' => true,
            'completed_at' => Carbon::now()
        ]);

        if (!$activate) {
            return false;
        }
        $this->userRepo->update($get_code_model[0]['user_id'], [
            'email_verified_at' => Carbon::now(),
        ]);
        $user = $this->userRepo->find('id', $get_code_model[0]['user_id'], ['email']);
        event(new AccountActivated($user[0]['email']));
        return $get_code_model[0]['user_id'];


    }*/

    public function expires(): Carbon
    {
        return Carbon::now()->subSeconds($this->expires);
    }

    /**
     * Check if the user is logged in
     * @return mixed
     */
    public function check()
    {
        return Auth::check();
    }

    //Remove expired generated codes

    public function removeExpired(): bool
    {
        $expires = $this->expires();

        return $this->activator
            ->where('completed', false)
            ->where('created_at', '<', $expires)
            ->delete();
    }


    //Create verification code
    public function createActivation($user): string
    {
        $code = $this->generateCode();
        $this->activator::create([
            'user_id' => $user->id,
            'code' => $code
        ]);
        return $code;
    }

    //Verification code generator

    public function generateCode(): string
    {
        return Str::random(10);
    }


//Create password reset code
    public function createPasswordReset($email)
    {
        $code = $this->generateCode();
        $this->password_reset::create([
            'email' => $email,
            'token' => $code
        ]);
    }

    //Complete Password reset process
    public function completeResetPassword($code, $password)
    {
        //Check if code has not expired
        $success = $this->password_reset::where('token', $code)
            ->where('created_at', '>', $this->expires());

        /*    if (!$success) {
                throw new InvalidCodeProvided('Your code has expired.');
            }*/
        $user = $this->userRepo->findTheFirstOne('email', $success->email, []);
        $hashedPassword = Hash::make($password);
        $user->update([
            'password' => $hashedPassword
        ]);
        //event(new ChangePassword($user));
    }

    /**
     * @param $user
     * @return bool
     * @throws NotActivatedException
     * Check if user account has been verified
     */
    public function checkActivation($user): bool
    {
        $completed = $user->email_verified_at;

        //if ($completed == null) {

        // throw new NotActivatedException('Your account has not been activated yet.');
        // }
        //  event(new HadRegistered($user));

        return true;
    }

    /**
     * Login with ID
     * @return mixed
     */
    public function loginWithId($id)
    {
        return Auth::loginUsingId($id);
    }


    /**
     * Login with User instance
     * @return mixed
     */
    public function loginWithUserInstance($user)
    {
        Auth::login($user);
        return Auth::user();
    }

}
