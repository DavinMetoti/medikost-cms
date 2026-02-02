<?php

namespace App\Http\Contracts\Auth;

use Illuminate\Http\Request;

interface RegisterRepositoryInterface
{
    /**
     * Register a new user and assign a role to the user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data);

    /**
     * Register a new user and assign a role to the user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function datatable(Request $request);

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function find($id);

    /**
     * Update the specified user.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data);

    /**
     * Delete the specified user.
     *
     * @param int $id
     * @return void
     */
    public function delete($id);
}
