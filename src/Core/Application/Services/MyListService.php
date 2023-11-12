<?php

namespace Core\Application\Services;

use Core\Application\Repositories\MyListRepository;
use Core\Domain\Entities\Content;
use Exception;

class MyListService
{
    private MyListRepository $myListRepository;

    public function __construct(MyListRepository $myListRepository) {
        $this->myListRepository = $myListRepository;
    }

    /**
     * Add a content item to a user's "My List."
     *
     * @param int $userId
     * @param int $contentId
     * @throws Exception
     */
    public function addToMyList(int $userId, int $contentId): void
    {
        $this->myListRepository->addToMyList($userId, $contentId);
    }

    /**
     * Remove a content item from a user's "My List."
     *
     * @param int $userId
     * @param int $contentId
     * @throws Exception
     */
    public function removeFromMyList(int $userId, int $contentId): void
    {
        $this->myListRepository->removeFromMyList($userId, $contentId);
    }

    /**
     * Get all content items in a user's "My List."
     *
     * @param int $userId
     * @return Content[]
     * @throws Exception
     */
    public function getMyList(int $userId): array
    {
        return $this->myListRepository->getMyList($userId);
    }

    public function processUserQuery(string $query, int $userId) :array
    {
        return $this->myListRepository->processQuery($query, $userId);
    }

    public function checkContent(int $userId, int $contentId) : bool
    {
        return $this->myListRepository->checkContent($userId, $contentId);
    }
}
