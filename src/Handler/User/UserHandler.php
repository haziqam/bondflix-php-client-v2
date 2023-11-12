<?php
namespace Handler\User;

use Core\Application\Services\AdminService;
use Exception;
use Handler\BaseHandler;
use Utils\ArrayMapper\ArrayMapper;
use Utils\Http\HttpStatusCode;
use Utils\Response\Response;

class UserHandler extends BaseHandler
{
    protected static UserHandler $instance;
    protected AdminService $service;
    private function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    public static function getInstance(AdminService $adminService): UserHandler
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                $adminService
            );
        }
        return self::$instance;
    }

    public function get($params = null): void
    {
        try {
            $resultArray = [];
            $page = isset($params['page']) ? intval($params['page']) : 1;
            $pageSize = isset($params['pageSize']) ? intval($params['pageSize']) : 10;
            if (isset($params['username'])) {
                $username = $params['username'];
                $singleUser = $this->service->getUserByUsername($username);
                if ($singleUser){
                    $resultArray[] = $singleUser->toArray();
                }
            } else {
                if (isset($params['query']) && isset($params['sortAscending'])) {
                    $query = $params['query'];
                    $sortAscending = filter_var($params['sortAscending'], FILTER_VALIDATE_BOOLEAN);

                    $result = $this->service->processUserQuery($query);
                    $filteredResult = [];

                    if (isset($params['isAdmin']) && isset($params['isSubscribed'])){
                        $isAdmin = filter_var($params['isAdmin'], FILTER_VALIDATE_BOOLEAN);
                        $isSubscribed = filter_var($params['isSubscribed'], FILTER_VALIDATE_BOOLEAN);
                        foreach ($result as $user) {
                            $filterConditions = [
                                ($user->getIsAdmin() === $isAdmin),
                                ($user->getIsSubscribed() === $isSubscribed),
                            ];
                            if (array_reduce($filterConditions, function($carry, $condition) {
                                return $carry && $condition;
                            }, true)) {
                                $filteredResult[] = $user;
                            }
                        }
                    } else {
                        $filteredResult = $result;
                    }

                    if ($sortAscending) {
                        usort($filteredResult, function ($a, $b) {
                            return $a->getUserId() - $b->getUserId();
                        });
                    } else {
                        usort($filteredResult, function ($a, $b) {
                            return $b->getUserId() - $a->getUserId();
                        });
                    }
                    $totalPages = ceil(count($filteredResult) / $pageSize);
                    header("X-Total-Pages: " . $totalPages);
                    $startIndex = ($page - 1) * $pageSize;
                    $pagedResult = array_slice($filteredResult, $startIndex, $pageSize);
                } else {
                    $users = $this->service->getAllUsers();
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
            $response = new Response(false, HttpStatusCode::NOT_FOUND, "Request failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }


    public function delete($params = null): void
    {
        try {
            if (isset($params['userId'])) {
                $user_id = $params['userId'];
                $user = $this->service->getUserById($user_id);
                if ($user){
                    /**
                     * Problem on deleting someone's session
                     * https://w3schools.invisionzone.com/topic/51237-destroy-session-of-other-user/
                     * Sol: https://w3schools.invisionzone.com/topic/9731-custom-session-save-handlers/
                     * Desc: Not gonna implement this yet.
                     */
                    $status = $this->service->deleteUserById($user_id);
                    if ($status) {
                        $response = new Response(true, HttpStatusCode::OK, "User(s) deletion success", $user->toArray());
                    } else {
                        $response = new Response(false, HttpStatusCode::NO_CONTENT, "User(s) deletion failed", null);
                    }
                } else {
                    $response = new Response(false, HttpStatusCode::NO_CONTENT, "User(s) deletion failed, user not found", null);
                }
            } else {
                $response = new Response(false, HttpStatusCode::NO_CONTENT, "User(s) deletion failed, user parameter id not found", null);
            }
            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "User(s) deletion failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }

    public function put($params = null): void
    {
        try {
            if (isset($params['userId'])) {

                $userId = $params['userId'];
                $username = $params['username'];
                $firstName = $params['first_name'];
                $lastName = $params['last_name'];
                $newPassword = $params['password'];

                $isAdmin = filter_var($params['is_admin'], FILTER_VALIDATE_BOOLEAN);
                $isSubscribed = filter_var($params['is_subscribed'], FILTER_VALIDATE_BOOLEAN);

                $user = $this->service->getUserById($userId);
                if ($user !== null) {
                    $user->setUsername($username);
                    $user->setFirstName($firstName);
                    $user->setLastName($lastName);
                    if (isset($newPassword) || $newPassword !== null){
                        $user->setPasswordHash($newPassword);
                    } else {
                        $user->setPasswordHash('');
                    }
                    $user->setIsAdmin($isAdmin);
                    $user->setIsSubscribed($isSubscribed);
                    $result =$this->service->updateUser($user);
                    if ($result) {
//                        $_SESSION['username'] = $user->getUsername();
//                        $_SESSION['first_name'] = $user->getFirstName();
//                        if (!$user->getLastName() !== null){
//                            $_SESSION['last_name'] = '';
//                        } else {
//                            $_SESSION['last_name'] = $user->getLastName();
//                        }
                        $response = new Response(true, HttpStatusCode::OK, "User update success", $user->toArray());
                    } else {
                        $response = new Response(false, HttpStatusCode::NO_CONTENT, "User update failed", null);
                    }
                } else {
                    $response = new Response(false, HttpStatusCode::NOT_FOUND, "User not found", null);
                }


            } else {
                $response = new Response(false, HttpStatusCode::BAD_REQUEST, "Invalid user data", null);
            }

            $response->encode_to_JSON();
            return;
        } catch (Exception $e) {
            $response = new Response(false, HttpStatusCode::BAD_REQUEST, "User update failed: " . $e->getMessage(), null);
            $response->encode_to_JSON();
            return;
        }
    }

}
