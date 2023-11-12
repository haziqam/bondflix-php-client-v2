<?php

namespace Middleware\Page;

class SubscribedCheck
{
    private static SubscribedCheck $instance;

    private function __construct(){}

    public static function getInstance(): SubscribedCheck
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __invoke(): bool
    {

        if (!isset($_SESSION['is_subscribed'])) {
            href("/subscribe");
        }

        if ($_SESSION['is_subscribed'] === false){
            href("/subscribe");
        }
        return true;
    }
}