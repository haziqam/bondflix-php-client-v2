<?php

namespace Core\Application\Repositories;

use Core\Domain\Entities\Genre;

interface GenreRepository
{
    public function createGenre(Genre $genre) : ?Genre;
    public function getGenreById(int $genre_id): ?Genre;
    public function updateGenre(Genre $genre) : ?Genre;
    public function deleteGenreById(int $genre_id);
    public function getAllGenre(): array;

    public function getAllContentIdFromGenreId(int $genreId) : array;

}