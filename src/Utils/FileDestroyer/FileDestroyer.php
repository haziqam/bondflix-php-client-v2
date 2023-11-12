<?php

namespace Utils\FileDestroyer;

class FileDestroyer
{
    private string $rootDir;

    public function __construct()
    {
        $this->rootDir = '/uploads/';
    }

    public function destroy($fileName, $folderName): bool
    {

        $targetDir = BASE_PATH . $this->rootDir . $folderName . '/';
        $filePath = $targetDir . $fileName;
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
