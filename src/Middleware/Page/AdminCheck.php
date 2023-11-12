<?php

namespace Middleware\Page;
use Exception;

class AdminCheck
{
    private static AdminCheck $instance;

    private function __construct(){}

    public static function getInstance(): AdminCheck
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
        if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            return true;
        }
        href("/login");
    }
}