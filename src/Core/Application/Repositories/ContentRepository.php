<?php
namespace Core\Application\Repositories;
use Core\Domain\Entities\Content;

interface ContentRepository {
    public function getContentById(int $content_id) : ?Content;
    public function createContent(Content $content) : ?Content;
    public function updateContent(Content $content) : ?Content;
    public function deleteContentById(int $content_id): void;
    public function getAllContents(?int $pageNumber, int $pageSize = 10): array;
    public function getActors(int $content_id): array;
    public function addActor(int $content_id, int $actor_id): void;
    public function deleteActor(int $content_id, int $actor_id): void;
    public function getCategories(int $content_id): array;
    public function addCategory(int $content_id, int $category_id): void;
    public function deleteCategory(int $content_id, int $category_id): void;
    public function getDirectors(int $content_id): array;
    public function addDirector(int $content_id, int $director_id): void;
    public function deleteDirector(int $content_id, int $director_id): void;
    public function getGenres(int $content_id): array;
    public function addGenre($content_id, $genre_id): void;
    public function deleteGenre($content_id, $genre_id) : void;
    public function processQuery(string $query): array;
}