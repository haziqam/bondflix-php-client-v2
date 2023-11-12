<?php

namespace Core\Application\Repositories;

use Core\Domain\Entities\Actor;

interface ActorRepository
{
    public function createActor(Actor $actor) : ?Actor;
    public function getActorById(int $actor_id) : ?Actor;

    public function updateActor(Actor $actor) : ?Actor;

    public function deleteActorById(int $actor_id);
}