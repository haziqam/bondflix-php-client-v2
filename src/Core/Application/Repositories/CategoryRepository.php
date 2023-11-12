<?php

namespace Core\Application\Repositories;

use Core\Domain\Entities\Category;

interface CategoryRepository
{
    public function createCategory(Category $category) : ?Category;
    public function getCategoryById(int $category_id) : ?Category;
    public function updateCategory(Category $category) : ?Category;
    public function deleteCategoryById(int $category_id);
    public function getAllCategory(): array;
}