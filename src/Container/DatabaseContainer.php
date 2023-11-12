<?php

namespace Container;

use Exception;
use PDO;
use Utils\Logger\Logger;

class DatabaseContainer
{
    private PDO $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getDb(): PDO
    {
        if (!isset($this->db)) {
            Logger::getInstance()->logMessage("Failed to load database");
            throw new Exception("Database not found in the container.");
        }
        return $this->db;
    }

    public function setDb(PDO $db): void
    {
        $this->db = $db;
    }


}