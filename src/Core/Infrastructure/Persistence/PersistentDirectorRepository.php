<?php

namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\DirectorRepository;
use Core\Domain\Entities\Director;

class PersistentDirectorRepository implements DirectorRepository
{

    public function createDirector(Director $director): ?Director
    {
        // TODO: Implement createDirector() method.
        return null;

    }

    public function getDirectorById(int $director_id): ?Director
    {
        // TODO: Implement getDirectorById() method.
        return null;

    }

    public function updateDirector(Director $director): ?Director
    {
        // TODO: Implement updateDirector() method.
        return null;

    }

    public function deleteDirectorById(int $director_id)
    {
        // TODO: Implement deleteDirectorById() method.
        return null;

    }
}