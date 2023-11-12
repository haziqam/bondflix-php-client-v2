<?php

namespace Core\Infrastructure\Persistence;

use Core\Application\Repositories\CategoryRepository;
use Core\Domain\Entities\Category;
use Exception;
use PDO;
use Utils\Logger\Logger;

class PersistentCategoryRepository implements CategoryRepository
{
    
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    public function createCategory(Category $category): ?Category
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO category (category_name)
                VALUES (:category_name)
            ");

            $categoryName = $category->getCategoryName();
            $stmt->bindParam(':category_name', $categoryName);

            if (!$stmt->execute()) {
                Logger::getInstance()->logMessage('Category creation failed');
                throw new Exception("Category creation failed");
            }

            $category->setCategoryId($this->getCategoryIdByName($categoryName)->getCategoryId());
            return $category;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Category creation failed: ' . $e->getMessage());
            throw new Exception("Category creation failed");
        }
    }

    public function getCategoryById(int $category_id): ?Category
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM category WHERE category_id = :category_id");
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                Logger::getInstance()->logMessage('Category fetch failed, id not found');
                throw new Exception("Category fetch failed");
            }

            return new Category($category_id, $result['category_name']);
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Error while fetching category by id: ' . $e->getMessage());
            throw new Exception("Error while fetching category by id");
        };
    }

    public function updateCategory(Category $category): ?Category
    {
        $stmt = $this->db->prepare("
            UPDATE category SET 
                category_name = :new_category_name
            WHERE category_id = :category_id"
        );

        $categoryId = $category->getCategoryId();
        $newCategoryName = $category->getCategoryName();

        $stmt->bindParam(':new_category_name', $newCategoryName);
        $stmt->bindParam('category_id', $categoryId);

        if (!$stmt->execute()) {
            throw new Exception("Category update failed");
        }

        return $category;

    }

    public function deleteCategoryById(int $category_id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM category
            WHERE category_id = :category_id
        ");

        $stmt->bindParam(':category_id', $category_id);

        if (!$stmt->execute()) {
            throw new Exception("Category deletion failed");
        }
        return true;

    }

    public function getAllCategory(): array 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT category_id, category_name
                FROM category
                ORDER BY category_id ASC;
            ");

            if (!$stmt->execute()) {
                throw new Exception("Database error while fetching category data");
            }

            $categorys = [];
            while ($categoryData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $category = new Category(
                    (int) $categoryData['category_id'],
                    $categoryData['category_name'],
                );

                $categorys[] = $category;
            }

            return $categorys;
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Failed to fetch all categorys: ' . $e->getMessage());
            throw new Exception("Failed to fetch all categorys");
        }
    }

    public function getCategoryIdByName(string $category_name) : ?Category
    {
        try {
            $stmt = $this->db->prepare("SELECT category_id FROM category WHERE category_name = :category_name");
            $stmt->bindParam(':category_name', $category_name);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                Logger::getInstance()->logMessage('Category fetch failed, no registered category');
                throw new Exception("Category fetch failed");
            }

            return new Category($result['category_id'], $category_name);
        } catch (Exception $e) {
            Logger::getInstance()->logMessage('Error while fetching category by name: ' . $e->getMessage());
            throw new Exception("Error while fetching category by name");
        }
    }
}