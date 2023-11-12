<?php

namespace Handler;

use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class APINotFoundHandler
{
    protected static APINotFoundHandler $instance;
    private function __construct()
    {
    }

    public static function getInstance(): APINotFoundHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
            );
        }
        return self::$instance;
    }

    public function __invoke(): bool
    {
        $response = new Response(false, HttpStatusCode::NOT_FOUND, "api not found", null);
        $response->encode_to_JSON();
        return false;
    }
}