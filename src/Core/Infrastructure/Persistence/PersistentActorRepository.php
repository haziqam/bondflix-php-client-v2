<?php

namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\ActorRepository;
use Core\Domain\Entities\Actor;

class PersistentActorRepository implements ActorRepository
{

    public function createActor(Actor $actor): ?Actor
    {
        // TODO: Implement createActor() method.
        return null;
    }

    public function getActorById(int $actor_id): ?Actor
    {
        // TODO: Implement getActorById() method.
        return null;

    }

    public function updateActor(Actor $actor): ?Actor
    {
        // TODO: Implement updateActor() method.
        return null;
    }

    public function deleteActorById(int $actor_id)
    {
        // TODO: Implement deleteActorById() method.
        return null;
    }
}