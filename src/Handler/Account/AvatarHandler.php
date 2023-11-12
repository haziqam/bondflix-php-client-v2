<?php

namespace Handler\Account;

use Core\Application\Services\AdminService;
use Core\Application\Services\UploadService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class AvatarHandler extends BaseHandler
{
    protected static AvatarHandler $instance;
    protected AdminService $adminService;
    protected UploadService $uploadService;
    private function __construct(AdminService $adminService, UploadService $uploadService)
    {
        $this->adminService = $adminService;
        $this->uploadService = $uploadService;
    }

    public static function getInstance(AdminService $adminService, UploadService $uploadService): AvatarHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $adminService,
                $uploadService
            );
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function post($params = null): void
    {
        try
        {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User session not found');
            }

            $user_id = $_SESSION['user_id'];
            $user = $this->adminService->getUserById($user_id);

            if (!$user) {
                throw new Exception('User not found');
            }

            if(!isset($_FILES["fileToUpload"])){
                throw new Exception('file not found');
            }

            $targetFile = basename($_FILES["fileToUpload"]["name"]);
            $fileType = $_FILES["fileToUpload"]["type"];
            $imageType = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

            if (!in_array($fileType, $imageType)) {
                throw new Exception('Invalid file type. Only image files are allowed.');
            }

            if ($user->getAvatarPath() !== null && $user->getAvatarPath() !== 'default.png') {
                if ($user->getAvatarPath() !== '') {
                    $status = $this->uploadService->deleteAvatar($user->getAvatarPath());
                    if (!$status){
                        throw new Exception('Failed to delete current avatar');
                    }
                }
                $newFileName = $this->uploadService->uploadAvatar($targetFile);

                $user->setAvatarPath($newFileName);

                $status = $this->adminService->updateUser($user);

                if (!$status) {
                    throw new Exception('Failed to update user with new avatar');
                }
            } else {
                throw new Exception('User avatar not initialized');
            }

            $response = new Response(true, HttpStatusCode::OK, "Update data success", null);
        } catch (Exception $e){
            $response = new Response(false, HttpStatusCode::OK, "Fail updating data: " . $e->getMessage(), null);
        }

        $response->encode_to_JSON();
    }


    public function get($params = null) : void
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User session not found');
            }

            $user_id = $_SESSION['user_id'];
            $user = $this->adminService->getUserById($user_id);

            if (!$user) {
                throw new Exception('User not found');
            }

            $filePath = $user->getAvatarPath();

            $response = new Response(true, HttpStatusCode::OK, "get data success", $filePath);
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Fail updating data: " . $e->getMessage(), null);
        }

        $response->encode_to_JSON();
    }

}