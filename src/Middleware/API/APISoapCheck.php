<?php

namespace Middleware\API;

use Exception;

class APISoapCheck
{
    private static APISoapCheck $instance;
    private function __construct(){}

    public static function getInstance(): APISoapCheck
    {
        if (!isset(self::$instance)) {
            self::$instance =  new static();
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function __invoke($path, $method): bool
    {
        $headers = getallheaders();
        if (!isset($headers['x-api-key'])) {
            return false;
        }

        if ($headers['x-api-key'] !== $_ENV['SOAP_API_KEY']) {
            return false;
        }

        return true;
    }

}