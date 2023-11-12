<?php

namespace Middleware\API;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class APIAdminCheck
{
    private static APIAdminCheck $instance;
    private function __construct()
    {
    }

    public static function getInstance() : APIAdminCheck
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __invoke($path, $method) : bool
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            return true;
        }

        $response = new Response(false, HttpStatusCode::UNAUTHORIZED, "not authorized", null);
        $response->encode_to_JSON();
        return false;
    }

}
