<?php

namespace Middleware\Page;

class NotSubscribedCheck
{
    private static NotSubscribedCheck $instance;

    private function __construct(){}

    public static function getInstance(): NotSubscribedCheck
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __invoke(): bool
    {

        if (isset($_SESSION['is_subscribed']) && $_SESSION['is_subscribed'] === true){
            href("/dashboard");
        }
        return true;
    }
}