<?php

define("BASE_PATH", __DIR__);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once BASE_PATH . '/Utils/EnvLoader/DotEnv.php';

use JetBrains\PhpStorm\NoReturn;
use Utils\EnvLoader\DotEnv;

/**
 * Setting up DotEnv reader
 */

try {
    $dotEnv = new DotEnv(BASE_PATH . '/.env');
    $dotEnv->load();
    $_ENV = $dotEnv->variables;
} catch (Exception $e) {
    echo "Fail to load .env file";
}

require_once BASE_PATH . '/Bootstrap/Autoloader.php';
require_once BASE_PATH . '/services.php';
require_once BASE_PATH . '/routes.php';





/**
 * redirect use for redirecting pages
 * @param $path
 * @param array $data
 * @return void
 */
function redirect($path, array $data = []): void
{
    extract($data);

    ob_start();
    include BASE_PATH . '/public/view/' . $path . '.php';
    $content = ob_get_clean();

    echo $content;
}

#[NoReturn] function href($url): void
{
    header("Location: $url");
    exit();
}