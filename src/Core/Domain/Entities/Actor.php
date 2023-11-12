<?php

namespace Core\Domain\Entities;

class Actor
{
    private int $actor_id;
    private string $first_name;
    private string $last_name;
    private string $birth_date;
    private string $gender;

    /**
     * @param int $actor_id
     * @param string $first_name
     * @param string $last_name
     * @param string $birth_date
     * @param string $gender
     */
    public function __construct(
        int $actor_id = -1,
        string $first_name = '',
        string $last_name = '',
        string $birth_date = '',
        string $gender = '')
    {
        $this->actor_id = $actor_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birth_date = $birth_date;
        $this->gender = $gender;
    }

    public function getActorId(): int
    {
        return $this->actor_id;
    }

    public function setActorId(int $actor_id): void
    {
        $this->actor_id = $actor_id;
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

    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    public function setBirthDate(string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function toArray(): array {
        return [
            'actor_id' => $this->actor_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender
        ];
    }
}