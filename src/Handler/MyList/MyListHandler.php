<?php

namespace Handler\MyList;

use Core\Application\Services\MyListService;
use Exception;
use Handler\BaseHandler;
use Utils\ArrayMapper\ArrayMapper;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class MyListHandler extends BaseHandler {

    protected static MyListHandler $instance;
    protected MyListService $service;

    private function __construct(MyListService $myListService)
    {
        $this->service = $myListService;
    }

    public static function getInstance($myListService): MyListHandler
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
            $resultArray = [];
            $page = isset($params['page']) ? intval($params['page']) : 1;
            $pageSize = isset($params['pageSize']) ? intval($params['pageSize']) : 10;
            if (isset($params['user_id'])) {
                $userId = $params['user_id'];
                if (isset($params['query']) && isset($params['sortAscending'])) {
                    $query = $params['query'];
                    $sortAscending = filter_var($params['sortAscending'], FILTER_VALIDATE_BOOLEAN);
                    $result = $this->service->processUserQuery($query, $userId);
                    $filteredResult = $result;
                    if ($sortAscending) {
                        usort($filteredResult, function ($a, $b) {
                            return $a->getContentId() - $b->getContentId();
                        });
                    } else {
                        usort($filteredResult, function ($a, $b) {
                            return $b->getContentId() - $a->getContentId();
                        });
                    }
                    $totalPages = ceil(count($filteredResult) / $pageSize);
                    header("X-Total-Pages: " . $totalPages);
                    $startIndex = ($page - 1) * $pageSize;
                    $pagedResult = array_slice($filteredResult, $startIndex, $pageSize);
                } else {
                    $users = $this->service->getMyList($userId);
                    $totalUsers = count($users);
                    $totalPages = ceil($totalUsers / $pageSize);
                    header("X-Total-Pages: " . $totalPages);
                    $page = max(1, min($page, $totalPages));

                    $startIndex = ($page - 1) * $pageSize;
                    $pagedResult = array_slice($users, $startIndex, $pageSize);
                }
                $resultArray = ArrayMapper::mapObjectsToArray($pagedResult);
            }

            if (!empty($resultArray)) {
                $response = new Response(true, HttpStatusCode::OK, "data retrieved successfully", $resultArray);
            } else {
                $response = new Response(false, HttpStatusCode::OK, "data not found", null);
            }
            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Request failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }

    protected function post($params = null): void
    {
        try {
            $userId = $params['user_id'];
            $contentId = $params['content_id'];

            if (!$this->service->checkContent($userId, $contentId)){
                $this->service->addToMyList($userId, $contentId);
                $response = new Response(true, HttpStatusCode::OK, "Content added to My List successfully", null);

            } else {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST, "already in list", null);
            }
            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Failed to add content to My List: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }

    protected function delete($params = null): void
    {
        try {
            $userId = $params['user_id'];
            $contentId = $params['content_id'];

            $this->service->removeFromMyList($userId, $contentId);

            $response = new Response(true, HttpStatusCode::OK, "Content removed from My List successfully", null);
            $response->encode_to_JSON();
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Failed to remove content from My List: " . $e->getMessage(), null);
            $response->encode_to_JSON();
        }
    }
}
