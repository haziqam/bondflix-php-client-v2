<?php

namespace Utils\Uploader;

interface IUploader
{
    public function upload($targetFile, $uploadDir);
}