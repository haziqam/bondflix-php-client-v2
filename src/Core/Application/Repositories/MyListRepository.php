<?php


namespace Core\Application\Repositories;

interface MyListRepository
{
    public function addToMyList(int $userId, int $contentId);
    public function removeFromMyList(int $userId, int $contentId);
    public function getMyList(int $userId): array;
    public function processQuery(string $query, int $userId): array;
    public function checkContent(int $userId, int $contentId): bool;
}