<?php

namespace Core\Domain\Entities;

class Genre
{
    public int $genre_id;

    public string $genre_name;

    /**
     * @param int $genre_id
     * @param string $genre_name
     */
    public function __construct(
        int $genre_id = -1,
        string $genre_name = '')
    {
        $this->genre_id = $genre_id;
        $this->genre_name = $genre_name;
    }


    public function getGenreId(): int
    {
        return $this->genre_id;
    }

    public function setGenreId(int $genre_id): void
    {
        $this->genre_id = $genre_id;
    }

    public function getGenreName(): string
    {
        return $this->genre_name;
    }

    public function setGenreName(string $genre_name): void
    {
        $this->genre_name = $genre_name;
    }

    public function toArray(): array {
        return [
            'genre_id' => $this->genre_id,
            'genre_name' => $this->genre_name
        ];
    }

}