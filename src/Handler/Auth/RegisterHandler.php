<?php
namespace Handler\Auth;

use Core\Application\Services\AuthService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class RegisterHandler extends BaseHandler
{
    protected static RegisterHandler $instance;
    protected AuthService $service;
    private function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public static function getInstance(AuthService $authService): RegisterHandler
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
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];

            $user = $this->service->register($username, $password, $first_name, $last_name);
            $response = new Response(true, HttpStatusCode::OK, "User registered successfully", $user->toArray());
            $response->encode_to_JSON();

        } catch (Exception $e) {

            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Registration failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}
