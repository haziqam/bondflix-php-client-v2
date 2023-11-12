<?php

namespace Utils\Uploader;
use Exception;
use Utils\GenerateRandomId;

class ImageUploader implements IUploader
{
    public array $types;
    public string $rootDir;
    const MAX_FILE_SIZE = 100000000;
    public function __construct()
    {
        $this->rootDir = '/uploads/';
        $this->types = ['jpeg', 'png', 'gif', 'jpg'];
    }

    /**
     * @throws Exception
     */
    public function upload($targetFile, $uploadDir): string
    {
        $targetDir = BASE_PATH . $this->rootDir . $uploadDir . '/';

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }


        $newFileName = (new GenerateRandomId)->generateRandomFileName($targetFile);

        $target_file = $targetDir . $newFileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            throw new Exception('File already exists');
        }

        if ($_FILES['fileToUpload']['size'] > ImageUploader::MAX_FILE_SIZE) {
            throw new Exception('File is too big');
        }

        if (!in_array($imageFileType, $this->types)) {
            throw new Exception('This file type is not supported');
        }

        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
        return $newFileName;
    }
}