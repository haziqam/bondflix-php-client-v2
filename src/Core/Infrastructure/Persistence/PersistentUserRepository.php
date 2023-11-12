<?php
namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\UserRepository;
use Core\Domain\Entities\User;
use Exception;
use PDO;
use Utils\Logger\Logger;

class PersistentUserRepository implements UserRepository
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function createUser(User $user): ?User
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (
                    first_name, 
                    last_name, 
                    username, 
                    password_hash,
                    is_admin,
                    is_subscribed) 
                VALUES (:first_name, :last_name, :username, :password_hash, :is_admin, :is_subscribed)");

            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $username = $user->getUsername();
            $passwordHash = $user->getPasswordHash();
            $isAdmin = $user->getIsAdmin();
            $isSubscribed = $user->getIsSubscribed();

            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password_hash', $passwordHash);
            $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_BOOL);
            $stmt->bindParam(':is_subscribed', $isSubscribed, PDO::PARAM_BOOL);

            if (!$stmt->execute()) {
                Logger::getInstance()->logMessage('User creation failed');
                throw new Exception("User creation failed");
            }

            $user->setUserId($this->getUserByUsername($username)->getUserId());
            return $user;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('User creation failed: ' . $e->getMessage());
            throw new Exception("User creation failed");
        }
    }

    /**
     * @throws Exception
     */
    public function getUserByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare("
            SELECT user_id, 
                   first_name, 
                   last_name, 
                   username, 
                   password_hash, 
                   is_admin, 
                   is_subscribed,
                   avatar_path
            FROM users 
            WHERE username = :username
        ");

        $stmt->bindParam(':username', $username);

        if (!$stmt->execute()) {
            throw new Exception("Database error while fetching user data");
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User(
            (int) $userData['user_id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['username'],
            $userData['password_hash'],
            (bool) $userData['is_admin'],
            (bool) $userData['is_subscribed'],
            (string) $userData['avatar_path']
        );
    }

    /**
     * @throws Exception
     */
    public function getUserById(string $user_id): ?User
    {
        $stmt = $this->db->prepare("
            SELECT user_id, 
                   first_name, 
                   last_name, 
                   username, 
                   password_hash, 
                   is_admin, 
                   is_subscribed,
                   avatar_path
            FROM users 
            WHERE user_id = :user_id
        ");

        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Database error while fetching user data");
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User(
            (int) $userData['user_id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['username'],
            $userData['password_hash'],
            (bool) $userData['is_admin'],
            (bool) $userData['is_subscribed'],
            (string) $userData['avatar_path']
        );
    }

    /**
     * @throws Exception
     */
    public function updateUser(User $user): User
    {
        $stmt = $this->db->prepare("
            UPDATE users SET 
                first_name = :first_name, 
                last_name = :last_name, 
                is_admin = :is_admin, 
                is_subscribed = :is_subscribed
                " . (!empty($user->getPasswordHash()) ? ", password_hash = :new_password" : "") . "
                " . (!empty($user->getAvatarPath()) ? ", avatar_path = :avatar_path" : "") . "
            WHERE user_id = :user_id
        ");

        $userId = $user->getUserId();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $isAdmin = $user->getIsAdmin();
        $isSubscribed = $user->getIsSubscribed();
        $newPasswordHash = $user->getPasswordHash();
        if (!empty($newPasswordHash)) {
            $stmt->bindParam(':new_password', $newPasswordHash);
        }

        $newAvatarPath = $user->getAvatarPath();
        if (!empty($newAvatarPath)){
            $stmt->bindParam(':avatar_path', $newAvatarPath);
        }

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_subscribed', $isSubscribed, PDO::PARAM_BOOL);

        if (!$stmt->execute()) {
            throw new Exception("User update failed");
        }

        return $user;
    }


    /**
     * @throws Exception
     */
    public function deleteUserByUsername(int $username): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM users
            WHERE username = :username
        ");

        $stmt->bindParam(':username', $username);

        if (!$stmt->execute()) {
            throw new Exception("User deletion failed");
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function deleteUserById(int $user_id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM users
            WHERE user_id = :user_id
        ");

        $stmt->bindParam(':user_id', $user_id);

        if (!$stmt->execute()) {
            throw new Exception("User deletion failed");
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function getAllUser(): array
    {
        try {
            $stmt = $this->db->prepare("
            SELECT user_id, 
                   first_name, 
                   last_name, 
                   username, 
                   password_hash, 
                   is_admin, 
                   is_subscribed,
                   avatar_path
            FROM users
            ORDER BY user_id ASC;
        ");

            if (!$stmt->execute()) {
                throw new Exception("Database error while fetching user data");
            }

            $users = [];
            while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User(
                    (int) $userData['user_id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['password_hash'],
                    (bool) $userData['is_admin'],
                    (bool) $userData['is_subscribed'],
                    (string) $userData['avatar_path']
                );

                $users[] = $user;
            }

            return $users;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all users: ' . $e->getMessage());
            throw new Exception("Failed to fetch all users");
        }
    }

    /**
     * @throws Exception
     */
    public function processQuery(string $query): array
    {
        try {
            $query = '%' . $query . '%';

            $stmt = $this->db->prepare("
            SELECT user_id, 
                   first_name, 
                   last_name, 
                   username, 
                   password_hash, 
                   is_admin, 
                   is_subscribed,
                   avatar_path
            FROM users
            WHERE (username LIKE :query
                OR first_name LIKE :query
                OR last_name LIKE :query);
        ");

            $stmt->bindParam(':query', $query);

            if (!$stmt->execute()) {
                throw new Exception("Database error while fetching user data");
            }

            $users = [];
            while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User(
                    (int) $userData['user_id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['password_hash'],
                    (bool) $userData['is_admin'],
                    (bool) $userData['is_subscribed'],
                    (string) $userData['avatar_path']
                );

                $users[] = $user;
            }

            return $users;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch users: ' . $e->getMessage());
            throw new Exception("Failed to fetch users: " . $e->getMessage());
        }
    }


}