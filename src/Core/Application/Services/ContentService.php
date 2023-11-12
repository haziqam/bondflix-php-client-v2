<?php

namespace Core\Application\Services;

use Core\Application\Repositories\ContentRepository;
use Core\Domain\Entities\Content;
use Exception;
use Utils\FileDestroyer\FileDestroyer;

class ContentService
{
    private ContentRepository $contentRepository;

    public function __construct($contentRepository) {
        $this->contentRepository = $contentRepository;
    }

    public function createContent(
        $title,
        $description,
        $release_date,
        $content_file_path,
        $thumbnail_file_path
    ): ?Content {
        $content = new Content();
        $content->setTitle($title);
        $content->setDescription($description);
        $content->setReleaseDate($release_date);
        $content->setContentFilePath($content_file_path);
        $content->setThumbnailFilePath($thumbnail_file_path);

        return $this->contentRepository->createContent($content);
    }

    public function removeContent($content_id, $thumbnailPath, $contentPath): void {
        $fileDestroyer = new FileDestroyer();
        $thumbnailPath = str_replace('/uploads/thumbnails/', '', $thumbnailPath);
        $contentPath = str_replace('/uploads/videos/', '', $contentPath);
        $fileDestroyer->destroy($thumbnailPath, 'thumbnails');
        $fileDestroyer->destroy($contentPath, 'videos');
        $this->contentRepository->deleteContentById($content_id);
    }

    /**
     * @throws Exception
     */
    public function updateContent(
        int $content_id, 
        ?string $title, 
        ?string $description, 
        ?string $release_date, 
        ?string $content_file_path,
        ?string $thumbnail_file_path
    ) : ?Content 
    {
        $updatedContent = $this->contentRepository->getContentById($content_id);

        if (is_null($updatedContent)) {
            throw new Exception("Content id not found");
        }

        if (!is_null($title)) $updatedContent->setTitle($title);
        if (!is_null($description)) $updatedContent->setDescription($description);
        if (!is_null($release_date)) $updatedContent->setReleaseDate($release_date);
        if (!is_null($content_file_path)) $updatedContent->setContentFilePath($content_file_path);
        if (!is_null($thumbnail_file_path)) $updatedContent->setThumbnailFilePath($thumbnail_file_path);

        return $this->contentRepository->updateContent($updatedContent);
    }

    public function getContentById(int $content_id): ?Content
    {
        return $this->contentRepository->getContentById($content_id);
    }

    public function getAllContents(?int $pageNumber): array
    {
        return $this->contentRepository->getAllContents($pageNumber);
    }

    public function getActors(int $content_id): array
    {
        return $this->contentRepository->getActors($content_id);
    }

    public function addActor(int $content_id, int $actor_id): void
    {
        $this->contentRepository->addActor($content_id, $actor_id);
    }
    public function removeActor(int $content_id, int $actor_id): void 
    {
        $this->contentRepository->deleteActor($content_id, $actor_id);
    }
    public function getCategories(int $content_id): array 
    {
        return $this->contentRepository->getCategories($content_id);
    }
    public function addCategory(int $content_id, int $category_id): void 
    {
        $this->contentRepository->addCategory($content_id, $category_id);
    }
    public function removeCategory(int $content_id, int $category_id): void 
    {
        $this->contentRepository->deleteCategory($content_id, $category_id);
    }
    public function getDirectors(int $content_id): array 
    {
        return $this->contentRepository->getDirectors($content_id);
    }
    public function addDirector(int $content_id, int $director_id): void 
    {
        $this->contentRepository->addDirector($content_id, $director_id);
    }
    public function removeDirector(int $content_id, int $director_id): void 
    {
        $this->contentRepository->deleteDirector($content_id, $director_id);
    }
    public function getGenres(int $content_id): array 
    {
        return $this->contentRepository->getGenres($content_id);
    }
    public function addGenre(int $content_id, int $genre_id): void 
    {
        $this->contentRepository->addGenre($content_id, $genre_id);
    }
    public function removeGenre(int $content_id, int $genre_id): void 
    {
        $this->contentRepository->deleteGenre($content_id, $genre_id);
    }

    public function processContentQuery(string $query) : array
    {
        return $this->contentRepository->processQuery($query);
    }
}