<?php
namespace Handler\Auth;

use Core\Application\Services\AuthService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class LoginHandler extends BaseHandler
{
    protected static LoginHandler $instance;
    protected AuthService $service;
    private function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public static function getInstance(AuthService $authService): LoginHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $authService
            );
        }
        return self::$instance;
    }

    public function post($params = null): void
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {

            $user = $this->service->login($username, $password);
            $response = new Response(true, HttpStatusCode::OK ,"User successfully logged in", $user->toArray());
            $response->encode_to_JSON();

        } catch (Exception $e) {

            $response = new Response(false, HttpStatusCode::FORBIDDEN, "Invalid credentials", null);
            $response->encode_to_JSON();

        }

    }
}