<?php
namespace Database;

use PDO;
use PDOException;
use Utils\Logger\Logger;

class Connection {
    private static ?PDO $dbInstance = null;
    private static Logger $logger;
    private static array $appliedMigrations = [];

    private function __construct(){
    }

    public static function getDBInstance(): ?PDO
    {
        if (self::$dbInstance === null || self::$logger === null) {
            self::$logger = Logger::getInstance();
            self::$dbInstance = self::connectDB();
        }
        return self::$dbInstance;
    }

    private static function connectDB(){
        try {
            $db_host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_NAME'];
            $db_port = $_ENV['DB_PORT'];
            $db_user = $_ENV['DB_USER'];
            $db_pass = $_ENV['DB_PASS'];

            $db = new PDO("pgsql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::runMigrations($db);
//            self::seedDB($db);
            return $db;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function &getAppliedMigrations(): array
    {
        return self::$appliedMigrations;
    }

    private static function runMigrations(PDO $db): void
    {
        self::extracted($db, "migrations");
    }

    private static function seedDB(PDO $db) {
        self::extracted($db, "seed");
    }

    /**
     * @param PDO $db
     * @return void
     */
    private static function extracted(PDO $db, string $type): void
    {
        $appliedMigrations = self::getAppliedMigrations();

        if ($type == "migrations") {
            $migrationsDirectory = __DIR__ . '/Migrations/';
        } else {
            $migrationsDirectory = __DIR__ . '/Seeds/';
        }

        foreach (glob($migrationsDirectory . '*.sql') as $migrationFile) {
            $migrationVersion = basename($migrationFile, '.sql');

            if (!in_array($migrationVersion, $appliedMigrations)) {
                $sql = file_get_contents($migrationFile);
                try {
                    $db->exec($sql);
                    self::$logger->logMessage('Successfully migrate' . $migrationFile);
                    self::$appliedMigrations[] = $migrationVersion;
                } catch (PDOException $e) {
                    self::$logger->logMessage($e->getMessage());
                }
            }
        }
    }
}
