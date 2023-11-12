<?php

namespace Utils;

class GenerateRandomId
{
    /**
     * Generate a unique random file name.
     *
     * @param string $originalFileName
     * @return string
     */
    public function generateRandomFileName(string $originalFileName): string
    {
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $uniqueId = uniqid();
        return $uniqueId . '.' . $extension;
    }
}