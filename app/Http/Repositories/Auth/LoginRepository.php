<?php

namespace App\Http\Repositories\Auth;

use App\Http\Contracts\Auth\LoginRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginRepository implements LoginRepositoryInterface
{
    /**
     * Attempt to log in the user with the provided credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool
    {
        return Auth::attempt($credentials); // Returns true if login is successful, otherwise false
    }

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout(); // Log the user out
    }
}
