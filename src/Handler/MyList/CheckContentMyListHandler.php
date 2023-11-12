<?php

namespace Handler\MyList;

use Core\Application\Services\MyListService;
use Exception;
use Handler\BaseHandler;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class CheckContentMyListHandler extends BaseHandler
{
    protected static CheckContentMyListHandler $instance;
    protected MyListService $service;

    private function __construct(MyListService $myListService)
    {
        $this->service = $myListService;
    }

    public static function getInstance(MyListService $myListService): CheckContentMyListHandler

    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $myListService
            );
        }
        return self::$instance;
    }
    protected function get($params = null): void
    {
        try {
            if (isset($params['user_id']) && isset($params['content_id'])){
                $bool = $this->service->checkContent($params['user_id'], $params['content_id']);
                $response = new Response(true, 200, "data successfully obtained", $bool);
            } else {
                $response = new Response(true, 200, "parameter error", false);
            }

            $response->encode_to_JSON();
            return;
        } catch (Exception $e){
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Request failed: " . $e->getMessage(), false);
            $response->encode_to_JSON();
            return;
        }
    }
}