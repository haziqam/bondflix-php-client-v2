<?php

namespace Container;
use Core\Application\Repositories\MyListRepository;
use Core\Application\Services\AdminService;
use Core\Application\Services\AuthService;
use Core\Application\Services\CategoryService;
use Core\Application\Services\ContentService;
use Core\Application\Services\GenreService;
use Core\Application\Services\MyListService;
use Core\Application\Services\UploadService;
use Exception;
use Utils\Logger\Logger;

class ServiceContainer
{
    private AuthService $authService;
    private AdminService $adminService;
    private ContentService $contentService;
    private GenreService $genreService;
    private UploadService $uploadService;
    private CategoryService $categoryService;
    private MyListService $myListService;



    /**
     * @param AuthService $authService
     * @param AdminService $adminService
     * @param ContentService $contentService
     * @param GenreService $genreService
     * @param CategoryService $categoryService
     */
    public function __construct(AuthService $authService, AdminService $adminService, ContentService $contentService, GenreService $genreService, CategoryService $categoryService, UploadService $uploadService, MyListService $myListService)
    {
        $this->authService = $authService;
        $this->adminService = $adminService;
        $this->contentService = $contentService;
        $this->genreService = $genreService;
        $this->uploadService= $uploadService;
        $this->categoryService = $categoryService;
        $this->myListService = $myListService;
    }

    /**
     * @throws Exception
     */
    public function getAuthService(): AuthService
    {
        if (!isset($this->authService)){
            Logger::getInstance()->logMessage("Failed to load AuthService service");
            throw new Exception("Service 'AuthService' not found in the container.");
        }
        return $this->authService;
    }

    public function setAuthService(AuthService $authService): void
    {
        $this->authService = $authService;
    }

    /**
     * @throws Exception
     */
    public function getAdminService(): AdminService
    {
        if (!isset($this->adminService)){
            Logger::getInstance()->logMessage("Failed to load AdminService service");
            throw new Exception("Service 'AdminService' not found in the container.");
        }
        return $this->adminService;
    }

    public function setAdminService(AdminService $adminService): void
    {
        $this->adminService = $adminService;
    }

    /**
     * @throws Exception
     */
    public function getContentService(): ContentService
    {
        if (!isset($this->contentService)){
            Logger::getInstance()->logMessage("Failed to load ContentService service");
            throw new Exception("Service 'ContentService' not found in the container.");
        }
        return $this->contentService;
    }

    public function setContentService(ContentService $contentService): void
    {
        $this->contentService = $contentService;
    }

    /**
     * @throws Exception
     */
    public function getGenreService(): GenreService
    {
        if (!isset($this->genreService)){
            Logger::getInstance()->logMessage("Failed to load GenreService service");
            throw new Exception("Service 'GenreService' not found in the container.");
        }
        return $this->genreService;
    }

    public function setGenreService(GenreService $genreService): void
    {
        $this->genreService = $genreService;
    }

    /**
     * @throws Exception
     */
    public function getUploadService(): UploadService
    {
        if (!isset($this->uploadService)){
            Logger::getInstance()->logMessage("Failed to load UploadService service");
            throw new Exception("Service 'UploadService' not found in the container.");
        }
        return $this->uploadService;
    }

    public function setUploadService(UploadService $uploadService): void
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @throws Exception
     */
    public function getCategoryService(): CategoryService {
        if (!isset($this->categoryService)){
            Logger::getInstance()->logMessage("Failed to load CategoryService service");
            throw new Exception("Service 'CategoryService' not found in the container.");
        }
        return $this->categoryService;
    }

    public function setCategoryService(CategoryService $categoryService): void {
        $this->categoryService = $categoryService;

    }

    /**
     * @throws Exception
     */
    public function getMyListService(): MyListService
    {
        if (!isset($this->myListService)){
            Logger::getInstance()->logMessage("Failed to load MyListService service");
            throw new Exception("Service 'MyListService' not found in the container.");
        }
        return $this->myListService;
    }

    public function setMyListService(MyListService $myListService): void
    {
        $this->myListService = $myListService;
    }
}
