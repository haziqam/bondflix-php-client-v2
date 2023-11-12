<?php

namespace Core\Domain\Entities;

class Category
{
    private int $category_id;
    private string $category_name;

    /**
     * @param int $category_id
     * @param string $category_name
     */
    public function __construct(
        int $category_id = -1,
        string $category_name = '')
    {
        $this->category_id = $category_id;
        $this->category_name = $category_name;
    }


    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    public function toArray(): array {
        return [
            'category_id' => $this->category_id,
            'category_name' => $this->category_name 
        ];
    }
}