<?php

namespace Core\Domain\Entities;

class User
{
    private int $user_id;
    private string $first_name;
    private string $last_name;
    private string $username;
    private string $password_hash;
    private bool $is_admin;
    private bool $is_subscribed;
    private string $avatar_path;

    /**
     * @param int $user_id
     * @param string $first_name
     * @param string $last_name
     * @param string $username
     * @param string $password_hash
     * @param bool $is_admin
     * @param bool $is_subscribed
     * @param string $avatar_path
     */
    public function __construct(
        int $user_id = -1,
        string $first_name = '',
        string $last_name = '',
        string $username = '',
        string $password_hash = '',
        bool $is_admin = false,
        bool $is_subscribed = false,
        string $avatar_path = 'default.png'
    )
    {
        $this->user_id = $user_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->is_admin = $is_admin;
        $this->is_subscribed = $is_subscribed;
        $this->avatar_path = $avatar_path;
    }


    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

    public function getIsSubscribed(): bool
    {
        return $this->is_subscribed;
    }

    public function setIsSubscribed(bool $is_subscribed): void
    {
        $this->is_subscribed = $is_subscribed;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
//            'password_hash' => $this->password_hash,
            'is_admin' => $this->is_admin,
            'is_subscribed' => $this->is_subscribed,
            'avatar_path' => $this->avatar_path
        ];
    }

    public function getAvatarPath(): string
    {
        return $this->avatar_path;
    }

    public function setAvatarPath(string $avatar_path): void
    {
        $this->avatar_path = $avatar_path;
    }

}
