<?php

namespace Container;

use Core\Application\Repositories\CategoryRepository;
use Core\Application\Repositories\ContentRepository;
use Core\Application\Repositories\GenreRepository;
use Core\Application\Repositories\MyListRepository;
use Core\Application\Repositories\UserRepository;
use Exception;
use Utils\Logger\Logger;

class RepositoryContainer
{
    private UserRepository $userRepository;
    private ContentRepository $contentRepository;
    private GenreRepository $genreRepository;
    private CategoryRepository $categoryRepository;
    private MyListRepository $myListRepository;



    /**
     * @param UserRepository $userRepository
     * @param ContentRepository $contentRepository
     * @param GenreRepository $genreRepository
     */
    public function __construct(UserRepository $userRepository, ContentRepository $contentRepository, GenreRepository $genreRepository, CategoryRepository $categoryRepository, MyListRepository $myListRepository)
    {
        $this->userRepository = $userRepository;
        $this->contentRepository = $contentRepository;
        $this->genreRepository = $genreRepository;
        $this->categoryRepository = $categoryRepository;
        $this->myListRepository = $myListRepository;
    }

    /**
     * Get the UserRepository
     *
     * @throws Exception
     */
    public function getUserRepository(): UserRepository
    {
        if (!isset($this->userRepository)) {
            Logger::getInstance()->logMessage("Failed to load UserRepository repository");
            throw new Exception("Repository 'UserRepository' not found in the container.");
        }
        return $this->userRepository;
    }

    public function setUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get the ContentRepository
     *
     * @throws Exception
     */
    public function getContentRepository(): ContentRepository
    {
        if (!isset($this->contentRepository)) {
            Logger::getInstance()->logMessage("Failed to load ContentRepository repository");
            throw new Exception("Repository 'ContentRepository' not found in the container.");
        }
        return $this->contentRepository;
    }

    public function setContentRepository(ContentRepository $contentRepository): void
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Get the GenreRepository
     *
     * @throws Exception
     */
    public function getGenreRepository(): GenreRepository
    {
        if (!isset($this->genreRepository)) {
            Logger::getInstance()->logMessage("Failed to load GenreRepository repository");
            throw new Exception("Repository 'GenreRepository' not found in the container.");
        }
        return $this->genreRepository;
    }

    public function setGenreRepository(GenreRepository $genreRepository): void
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * @throws Exception
     */
    public function getCategoryRepository(): CategoryRepository
    {
        if (!isset($this->categoryRepository)) {
            Logger::getInstance()->logMessage("Failed to load CategoryRepository repository");
            throw new Exception("Repository 'CategoryRepository' not found in the container.");
        }
        return $this->categoryRepository;
    }

    public function setCategoryRepository(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws Exception
     */
    public function getMyListRepository(): MyListRepository
    {
        if (!isset($this->myListRepository)) {
            Logger::getInstance()->logMessage("Failed to load MyListRepository repository");
            throw new Exception("Repository 'MyListRepository' not found in the container.");
        }
        return $this->myListRepository;
    }

    public function setMyListRepository(MyListRepository $myListRepository): void
    {
        $this->myListRepository = $myListRepository;
    }
}
