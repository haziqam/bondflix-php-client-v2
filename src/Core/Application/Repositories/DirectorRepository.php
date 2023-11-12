<?php

namespace Core\Application\Repositories;

use Core\Domain\Entities\Director;

interface DirectorRepository
{
    public function createDirector(Director $director) : ?Director;
    public function getDirectorById(int $director_id) : ?Director;

    public function updateDirector(Director $director) : ?Director;

    public function deleteDirectorById(int $director_id);
}