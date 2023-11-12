<?php

namespace Core\Application\Services;

use Core\Domain\Entities\Category;
use Core\Infrastructure\Persistence\PersistentCategoryRepository;
use Exception;

class CategoryService
{
    private PersistentCategoryRepository $categoryRepository;

    public function __construct($categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }
    public function getAllCategory(): array {
        return $this->categoryRepository->getAllCategory();
    }

    public function getCategoryById(int $category_id): ?Category {
        return $this->categoryRepository->getCategoryById($category_id);
    }
    public function removeCategory(int $category_id): void {
        $this->categoryRepository->deleteCategoryById($category_id);
    }

    /**
     * @throws Exception
     */
    public function addCategory(string $category_name): ?Category {
        $newCategory = new Category();
        $newCategory->setCategoryName($category_name);
        return $this->categoryRepository->createCategory($newCategory);
    }

    public function updateCategory($category_id, $category_name): ?Category {
        $category = $this->categoryRepository->getCategoryById($category_id);
        $category->setCategoryName($category_name);
        return $this->categoryRepository->updateCategory($category);
    }
}