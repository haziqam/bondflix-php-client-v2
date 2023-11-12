<?php

namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\MyListRepository;
use Core\Domain\Entities\Content;
use Exception;
use PDO;
use PDOException;
use Utils\Logger\Logger;

class PersistentMyListRepository implements MyListRepository
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function addToMyList(int $userId, int $contentId): void
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO my_list (user_id, content_id)
                VALUES (:user_id, :content_id)
            ");

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':content_id', $contentId);

            if (!$stmt->execute()) {
                throw new Exception("Failed to add content to My List");
            }
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to add content to My List: ' . $e->getMessage());
            throw new Exception("Failed to add content to My List: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function removeFromMyList(int $userId, int $contentId): void
    {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM my_list
                WHERE user_id = :user_id
                AND content_id = :content_id
            ");

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':content_id', $contentId);

            if (!$stmt->execute()) {
                throw new Exception("Failed to remove content from My List");
            }
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to remove content from My List: ' . $e->getMessage());
            throw new Exception("Failed to remove content from My List: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getMyList(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT c.content_id, c.title, c.description, c.release_date, c.content_file_path, c.thumbnail_file_path
                FROM my_list m
                JOIN content c ON m.content_id = c.content_id
                WHERE m.user_id = :user_id
            ");

            $stmt->bindParam(':user_id', $userId);

            if (!$stmt->execute()) {
                throw new Exception("Failed to fetch My List data");
            }

            $myList = [];
            while ($contentData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $content = new Content(
                    (int) $contentData['content_id'],
                    $contentData['title'],
                    $contentData['description'],
                    $contentData['release_date'],
                    $contentData['content_file_path'],
                    $contentData['thumbnail_file_path']
                );

                $myList[] = $content;
            }

            return $myList;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch My List data: ' . $e->getMessage());
            throw new Exception("Failed to fetch My List data: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function processQuery(string $query, int $userId): array
    {
        try {
            $query = '%' . $query . '%';
            $stmt = $this->db->prepare("
            SELECT c.content_id, c.title, c.description, c.release_date, c.content_file_path, c.thumbnail_file_path
            FROM my_list m
            JOIN content c ON m.content_id = c.content_id
            WHERE m.user_id = :user_id
            AND (c.title LIKE :query OR c.description LIKE :query)
        ");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':query', $query);

            if (!$stmt->execute()) {
                throw new Exception("Failed to fetch My List data");
            }

            $myList = [];
            while ($contentData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $content = new Content(
                    (int) $contentData['content_id'],
                    $contentData['title'],
                    $contentData['description'],
                    $contentData['release_date'],
                    $contentData['content_file_path'],
                    $contentData['thumbnail_file_path']
                );

                $myList[] = $content;
            }


            return $myList;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch My List data: ' . $e->getMessage());
            throw new Exception("Failed to fetch My List data: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function checkContent(int $userId, int $contentId): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM my_list WHERE user_id = :userId AND content_id = :contentId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':contentId', $contentId, PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            throw new Exception("Failed to check content: " . $e->getMessage());
        }
    }

}