<?php

namespace Middleware\Page;
use Exception;

class LoggedInCheck
{
    private static LoggedInCheck $instance;

    private function __construct(){}

    public static function getInstance(): LoggedInCheck
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function __invoke($path, $method): bool
    {
        if (!isset($_SESSION['user_id'])) {
            href("/login");
        }
        return true;
    }
}