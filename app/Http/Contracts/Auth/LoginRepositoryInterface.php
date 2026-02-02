<?php

namespace App\Http\Contracts\Auth;

interface LoginRepositoryInterface
{
    /**
     * Attempt to log in the user with the provided credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool;

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void;
}
