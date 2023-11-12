<?php

namespace Core\Application\Repositories;
use Core\Domain\Entities\User;

interface UserRepository
{
    public function createUser(User $user): ?User;
    public function getUserByUsername(string $username): ?User;
    public function getUserById(string $user_id): ?User;
    public function updateUser(User $user): ?User;
    public function deleteUserByUsername(int $username) : bool;
    public function deleteUserById(int $user_id) : bool;
    public function getAllUser() : array;
}