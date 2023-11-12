<?php

/**
 * View this reference: https://www.youtube.com/watch?v=2_dqDpSSpsc
 */

namespace Router;

use Utils\Http\HttpMethod;

class Router
{

    private array $handlers;
    private $pageNotFoundHandler;
    private $apiNotFoundHandler;

    public function addAPI(string $path, string $method, $handler, array $middlewares = []): void
    {
        $this->addRoute($method, $path, $handler, $middlewares);
    }

    public function addPage(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute(HttpMethod::GET, $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares = []): void
    {
        $this->handlers[] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function setPageNotFoundHandler($handler): void
    {
        $this->pageNotFoundHandler = $handler;
    }

    public function setApiNotFoundHandler($handler): void
    {
        $this->apiNotFoundHandler = $handler;
    }

    public function run(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $rawInput = file_get_contents("php://input");



        parse_str($rawInput, $requestData);
        $urlParams = array_merge($_GET, $_POST, $requestData, $_FILES);

        $callback = null;
        $isApiRoute = false;
        $methodFound = false;
        $middlewares = [];

        foreach ($this->handlers as $handler) {
            if (strpos($requestPath, '/api/') === 0) {
                $isApiRoute = true;
                if ($handler['path'] === $requestPath && $method === $handler['method']) {
                    $callback = $handler['handler'];
                    $methodFound = true;
                    $middlewares = $handler['middlewares'];
                    break;
                }
            } else {
                if ($handler['path'] === $requestPath && $method === $handler['method']){
                    $callback = $handler['handler'];
                    $middlewares = $handler['middlewares'];
                    break;
                }
            }
        }

        if (!$callback) {
            header("HTTP/1.0 404 Not Found");
            if ($isApiRoute && !empty($this->apiNotFoundHandler)) {
                $callback = $this->apiNotFoundHandler;
            } else {
                $callback = $this->pageNotFoundHandler;
            }
        }

        foreach ($middlewares as $middleware) {
            if (!$middleware($requestPath, $method)) {
                return;
            }
        }

        if ($isApiRoute) {
            if ($methodFound) {
                $callback->handle($method, $urlParams);
            } else {
                call_user_func_array($callback, [$urlParams]);
            }
        } else {
            call_user_func_array($callback, [$urlParams]);
        }
    }
}