<?php

namespace Utils\Logger;

class Logger
{
    private static Logger $instance;
    private static string $log_path = BASE_PATH . '/logs/logfile.txt';
    public function __constructor() {
    }

    public static function getInstance() : Logger {
        if(!isset(self::$instance)){
            self::$instance = new static ();
        }
        return self::$instance;
    }

    public static function getLogPath(): string
    {
        return self::$log_path;
    }

    public static function logMessage($exception) {
        $logFile = self::getLogPath();
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "$timestamp: " . $exception . "\n";

        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}