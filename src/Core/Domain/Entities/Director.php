<?php

namespace Core\Domain\Entities;

class Director
{
    private int $director_id;
    private string $first_name;
    private string $last_name;

    /**
     * @param int $director_id
     * @param string $first_name
     * @param string $last_name
     */
    public function __construct(
        int $director_id = -1,
        string $first_name = '',
        string $last_name = '')
    {
        $this->director_id = $director_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function getDirectorId(): int
    {
        return $this->director_id;
    }

    public function setDirectorId(int $director_id): void
    {
        $this->director_id = $director_id;
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

    public function toArray(): array {
        return [
            'director_id' => $this->director_id, 
            'first_name '=> $this->first_name,
            'last_name' => $this->last_name
        ];
    }
}