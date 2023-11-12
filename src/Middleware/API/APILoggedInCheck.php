<?php

namespace Middleware\API;
use Exception;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class APILoggedInCheck
{
    private static APILoggedInCheck $instance;

    private function __construct(){}

    public static function getInstance(): APILoggedInCheck
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
            $response = new Response(false, HttpStatusCode::UNAUTHORIZED, "not authorized", null);
            $response->encode_to_JSON();
            return false;
        }
        return true;
    }
}