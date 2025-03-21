<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Create a new user in the database
     *
     * @param array $data Required data to create user (e.g., name, email, password)
     * @return User The created object
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find a user by ID
     *
     * @param int $id User ID
     * @return User|null The user if found, or null if not found
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a user by ID or throw an exception if not found
     *
     * @param int $id User ID
     * @return User The requested object
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException if user not found
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a user by email
     *
     * @param string $email User's email
     * @return User|null The user if found, or null if not found
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Update an existing user's data
     *
     * @param User $user The object to update
     * @param array $data The new data
     * @return User The updated object
     */
    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    /**
     * Delete a user from the database
     *
     * @param User $user The object to delete
     * @return void
     */
    public function delete(User $user)
    {
        $user->delete();
    }

    /**
     * Retrieve all users
     *
     * @return \Illuminate\Database\Eloquent\Collection List of all users
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Count the number of users in the database
     *
     * @return int Number of users
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Retrieve users by role
     *
     * @param string $role The requested role (e.g., candidate, recruiter, admin)
     * @return \Illuminate\Database\Eloquent\Collection List of users with this role
     */
    public function getByRole($role)
    {
        return $this->model->where('role', $role)->get();
    }
}