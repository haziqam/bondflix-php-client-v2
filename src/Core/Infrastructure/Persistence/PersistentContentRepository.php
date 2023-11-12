<?php

namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\ContentRepository;
use Core\Domain\Entities\Actor;
use Core\Domain\Entities\Category;
use Core\Domain\Entities\Content;
use Core\Domain\Entities\Director;
use Core\Domain\Entities\Genre;
use Exception;
use PDO;
use Utils\Logger\Logger;

class PersistentContentRepository implements ContentRepository
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getContentById(int $content_id): ?Content
    {
        $stmt = $this->db->prepare("
            SELECT 
                content_id,
                title,
                description,
                release_date,
                content_file_path,
                thumbnail_file_path
            FROM content
            WHERE content_id = :content_id
        ");

        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Database error while fetching content data");
        }

        $contentData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contentData) {
            return null;
        }

        return new Content(
            (int) $contentData['content_id'],
            $contentData['title'],
            $contentData['description'],
            $contentData['release_date'],
            $contentData['content_file_path'],
            $contentData['thumbnail_file_path']
        );
    }

    public function createContent(Content $content): ?Content
    {
        $stmt = $this->db->prepare("
        INSERT INTO content (
            title, 
            description, 
            release_date,
            content_file_path,
            thumbnail_file_path
        ) 
        VALUES (:title, :description, :release_date, :content_file_path, :thumbnail_file_path)");

        $title = $content->getTitle();
        $description = $content->getDescription();
        $release_date = $content->getReleaseDate();
        $content_file_path = $content->getContentFilePath();
        $thumbnail_file_path = $content->getThumbnailFilePath();

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':release_date', $release_date);
        $stmt->bindParam(':content_file_path', $content_file_path);
        $stmt->bindParam(':thumbnail_file_path', $thumbnail_file_path);

        if (!$stmt->execute()) {
            throw new Exception("Content creation failed");
        }

        $content->setContentId($this->getLastContentId());
        return $content;
    }

    public function updateContent(Content $content): ?Content
    {
        $stmt = $this->db->prepare("
            UPDATE content SET 
                title = :new_title,
                description = :new_description,
                release_date = :new_release_date,
                content_file_path = :new_content_file_path,
                thumbnail_file_path = :new_thumbnail_file_path
            WHERE content_id = :content_id
        ");

        $contentId = $content->getContentId();
        $newTitle = $content->getTitle();
        $newDescription = $content->getDescription();
        $newReleaseDate = $content->getReleaseDate();
        $newContentFilePath = $content->getContentFilePath();
        $newThumbnailFilePath = $content->getThumbnailFilePath();

        $stmt->bindParam(':new_title', $newTitle);
        $stmt->bindParam(':new_description', $newDescription);
        $stmt->bindParam(':new_release_date', $newReleaseDate);
        $stmt->bindParam(':new_content_file_path', $newContentFilePath);
        $stmt->bindParam(':new_thumbnail_file_path', $newThumbnailFilePath);
        $stmt->bindParam(':content_id', $contentId);

        if (!$stmt->execute()) {
            throw new Exception("Content update failed");
        }

        return $content;
    }

    public function deleteContentById(int $content_id): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM content
            WHERE content_id = :content_id;
        ");

        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Content deletion failed");
        }
    }

    public function getAllContents(?int $pageNumber, int $pageSize = 10): array
    {
        $query = "SELECT * FROM content";

        if (!is_null($pageNumber)) {
            $query .= " LIMIT :pageSize OFFSET :offset";
        }
    
        $stmt = $this->db->prepare($query);
    
        if (!is_null($pageNumber)) {
            $offset = ($pageNumber - 1) * $pageSize;
            $stmt->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch content data");
        }

        $contents = [];
        while ($contentData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $content = new Content(
                (int) $contentData['content_id'],
                $contentData['title'],
                $contentData['description'],
                $contentData['release_date'],
                $contentData['content_file_path'],
                $contentData['thumbnail_file_path']
            );
            
            $contents[] = $content;
        }
        
        return $contents;
    }

    public function getActors(int $content_id): array 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT a.actor_id, a.first_name, a.last_name, a.birth_date, a.gender
                FROM content c
                JOIN actor_content ac ON c.content_id = ac.content_id
                JOIN actor a ON ac.actor_id = a.actor_id
                WHERE c.content_id = :content_id
            ");

        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch actors data from content");
        }

        $actors = [];
        while ($actorData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $actor = new Actor(
                (int) $actorData['actor_id'],
                $actorData['first_name'],
                $actorData['last_name'],
                $actorData['birth_date'],
                $actorData['gender']
            );

            $actors[] = $actor; 
        }

        return $actors;

        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all content actors: ' . $e->getMessage());
            throw new Exception("Failed to fetch content actors");
        }
    }

    public function addActor(int $content_id, int $actor_id): void 
    {
        $stmt = $this->db->prepare("
            INSERT INTO actor_content (actor_id, content_id)
            VALUES (:actor_id, :content_id)
        ");

        $stmt->bindParam(':actor_id', $actor_id);
        $stmt->bindParam('content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add actor");
        }
    }

    public function deleteActor(int $content_id, int $actor_id): void 
    {
        $stmt = $this->db->prepare("
            DELETE FROM actor_content
            WHERE actor_id = :actor_id
            AND content_id = :content_id
        ");

        $stmt->bindParam(':actor_id', $actor_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete actor");
        }
    }

    public function getCategories(int $content_id): array 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT ctg.category_id, ctg.category_name
                FROM content c
                JOIN category_content cc ON c.content_id = cc.content_id
                JOIN category ctg ON cc.category_id = ctg.category_id
                WHERE c.content_id = :content_id
            ");


            $stmt->bindParam(':content_id', $content_id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to fetch category data from content");
            }

            $categories = [];
            while ($directorData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $category = new Category(
                    (int) $directorData['category_id'],
                    $directorData['category_name']
                );

                $categories[] = $category; 
            }

            return $categories;

        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all content categories: ' . $e->getMessage());
            throw new Exception("Failed to fetch content categories");
        }
    }

    public function addCategory(int $content_id, int $category_id): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO category_content (category_id, content_id)
            VALUES (:category_id, :content_id)
        ");

        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add category");
        }
    }

    public function deleteCategory(int $content_id, int $category_id): void 
    {
        $stmt = $this->db->prepare("
            DELETE FROM category_content
            WHERE category_id = :category_id
            AND content_id = :content_id
        ");

        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete category");
        }
    }

    public function getDirectors(int $content_id): array 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT d.director_id, d.first_name, d.last_name
                FROM content c
                JOIN director_content dc ON c.content_id = dc.content_id
                JOIN director d ON dc.director_id = d.director_id 
                WHERE c.content_id = :content_id
            ");


            $stmt->bindParam(':content_id', $content_id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to fetch director data from content");
            }

            $directors = [];
            while ($directorData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $director = new Director(
                    (int) $directorData['director_id'],
                    $directorData['first_name'],
                    $directorData['last_name']
                );

                $directors[] = $director; 
            }

            return $directors;

        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all content directors: ' . $e->getMessage());
            throw new Exception("Failed to fetch content directors");
        }
    }

    public function addDirector(int $content_id, int $director_id): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO director_content (director_id, content_id)
            VALUES (:director_id, :content_id)
        ");

        $stmt->bindParam(':director_id', $director_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add director");
        }
    }

    public function deleteDirector(int $content_id, int $director_id): void 
    {
        $stmt = $this->db->prepare("
            DELETE FROM director_content
            WHERE director_id = :director_id
            AND content_id = :content_id
        ");

        $stmt->bindParam(':director_id', $director_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete director");
        }
    }

    public function getGenres(int $content_id): array 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT g.genre_id, g.genre_name
                FROM content c
                JOIN genre_content gc ON c.content_id = gc.content_id
                JOIN genre g ON gc.genre_id = g.genre_id 
                WHERE c.content_id = :content_id
            ");


            $stmt->bindParam(':content_id', $content_id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to fetch genre data from content");
            }

            $genres = [];
            while ($genreData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $genre = new Genre(
                    (int) $genreData['genre_id'],
                    $genreData['genre_name']
                );

                $genres[] = $genre; 
            }

            return $genres;

        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all content genres: ' . $e->getMessage());
            throw new Exception("Failed to fetch content genres");
        }
    }

    public function addGenre($content_id, $genre_id): void 
    {
        $stmt = $this->db->prepare("
        INSERT INTO genre_content (genre_id, content_id)
        VALUES (:genre_id, :content_id)
        ");

        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add genre");
        }
    }

    public function deleteGenre($content_id, $genre_id) : void 
    {
        $stmt = $this->db->prepare("
        DELETE FROM genre_content
        WHERE genre_id = :genre_id
        AND content_id = :content_id
        ");

        $stmt->bindParam(':genre_id', $genre_id);
        $stmt->bindParam(':content_id', $content_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete genre");
        }
    }

    private function getLastContentId(): int
    {
        $stmt = $this->db->prepare("
            SELECT MAX(content_id) as max_content_id
            FROM content
        ");

        if (!$stmt->execute()) {
            throw new Exception("Failed to find last content id");
        }

        $maxContentData = $stmt->fetch();
        if (is_null($maxContentData['max_content_id'])) {
            return 0;
        }

        return (int) $maxContentData['max_content_id'];
    }


    /**
     * @throws Exception
     */
    public function processQuery(string $query): array
    {
        try {
            $query = '%' . $query . '%';

            $stmt = $this->db->prepare("
            SELECT content_id, 
                   title, 
                   description, 
                   release_date, 
                   content_file_path, 
                   thumbnail_file_path
            FROM content
            WHERE (title LIKE :query OR description LIKE :query);
        ");

            $stmt->bindParam(':query', $query);

            if (!$stmt->execute()) {
                throw new Exception("Database error while fetching data");
            }

            $contents = [];
            while ($contentData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $content = new Content(
                    (int) $contentData['content_id'],
                    $contentData['title'],
                    $contentData['description'],
                    $contentData['release_date'],
                    $contentData['content_file_path'],
                    $contentData['thumbnail_file_path'],
                );

                $contents[] = $content;
            }

            return $contents;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch data: ' . $e->getMessage());
            throw new Exception("Failed to fetch data: " . $e->getMessage());
        }
    }
}