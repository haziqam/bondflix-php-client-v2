<?php

namespace Core\Application\Services;
use Core\Application\Repositories\UserRepository;
use Core\Domain\Entities\User;
use Exception;

/**
 * This repository will include 4 repositories at minimum:
 * GenreRepo, CategoryRepo, DirectorRepo, ActorRepo
 */

class AdminService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    function getAllUsers() : array {
        return $this->userRepository->getAllUser();
    }

    function getUserByUsername(string $username): ?User
    {
        return $this->userRepository->getUserByUsername($username);
    }

    function getUserById(string $user_id): ?User
    {
        return $this->userRepository->getUserById($user_id);
    }

    function deleteUserById(string $user_id) : bool
    {
        return $this->userRepository->deleteUserById($user_id);
    }

    function updateUser(User $user): ?User
    {
        if ($user->getPasswordHash() !== ''){
            $hashed_password = password_hash($user->getPasswordHash(), PASSWORD_BCRYPT, [12]);
            $user->setPasswordHash($hashed_password);
        }
        return $this->userRepository->updateUser($user);
    }

    /**
     * @throws Exception
     */
    function processUserQuery(string $query) : array
    {
        return $this->userRepository->processQuery($query);
    }
}