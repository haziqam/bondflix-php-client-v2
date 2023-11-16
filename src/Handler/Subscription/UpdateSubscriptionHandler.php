<?php

namespace Handler\Subscription;

use Core\Application\Services\AdminService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class UpdateSubscriptionHandler extends BaseHandler
{
    protected static UpdateSubscriptionHandler $instance;
    protected AdminService $service;

    private function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    public static function getInstance(AdminService $adminService): UpdateSubscriptionHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $adminService
            );
        }
        return self::$instance;
    }


    protected function post($params = null): void
    {
        try {
            if (!isset($params['user_id'])){
                $response = new Response(false, 500, "user update failed", false);
                $response->encode_to_JSON();
                return;
            }

            $user = $this->service->getUserById($params['user_id']);

            if (!$user) {
                $response = new Response(false, 404, "user not found", false);
                $response->encode_to_JSON();
                return;
            }

            $user->setIsSubscribed(true);
            $this->service->updateUser($user);

            $response = new Response(true, 200, "user update success", true);
            $response->encode_to_JSON();
            return;

        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Request failed " . $e->getMessage(), false);
            $response->encode_to_JSON();
            return;
        }
    }

}