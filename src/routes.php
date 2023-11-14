<?php
global $routes;
global $serviceContainer;

use Handler\Account\AccountHandler;
use Handler\Account\AvatarHandler;
use Handler\APINotFoundHandler;
use Handler\Auth\LoginHandler;
use Handler\Auth\LogoutHandler;
use Handler\Auth\RegisterHandler;
use Handler\Category\CategoryHandler;
use Handler\Content\ContentActorHandler;
use Handler\Content\ContentCategoryHandler;
use Handler\Content\ContentDirectorHandler;
use Handler\Content\ContentGenreHandler;
use Handler\Content\ContentHandler;
use Handler\Genre\GenreHandler;
use Handler\MyList\CheckContentMyListHandler;
use Handler\MyList\MyListHandler;
use Handler\Upload\UploadHandler;
use Handler\User\UserHandler;
use Middleware\API\APIAdminCheck;
use Middleware\API\APILoggedInCheck;
use Middleware\Page\AdminCheck;
use Middleware\Page\LoggedInCheck;
use Middleware\Page\NotSubscribedCheck;
use Middleware\Page\SubscribedCheck;
use Router\Router;
use Utils\Logger\Logger;


/**
 * Registering the singleton handlers
 */
try {
    $loginHandler = LoginHandler::getInstance($serviceContainer->getAuthService());
    $registerHandler = RegisterHandler::getInstance($serviceContainer->getAuthService());
    $logoutHandler = LogoutHandler::getInstance($serviceContainer->getAuthService());
    $contentHandler = ContentHandler::getInstance($serviceContainer->getContentService(), $serviceContainer->getGenreService());
    $contentActorHandler = ContentActorHandler::getInstance($serviceContainer->getContentService());
    $contentCategoryHandler = ContentCategoryHandler::getInstance($serviceContainer->getContentService());
    $contentDirectorHandler = ContentDirectorHandler::getInstance($serviceContainer->getContentService());
    $contentGenreHandler = ContentGenreHandler::getInstance($serviceContainer->getContentService(), $serviceContainer->getGenreService());
    $genreHandler = GenreHandler::getInstance($serviceContainer->getGenreService());
    $uploadHandler = UploadHandler::getInstance($serviceContainer->getUploadService());
    $categoryHandler = CategoryHandler::getInstance($serviceContainer->getCategoryService());
    $userHandler = UserHandler::getInstance($serviceContainer->getAdminService());
    $accountHandler = AccountHandler::getInstance($serviceContainer->getAdminService());
    $avatarHandler = AvatarHandler::getInstance($serviceContainer->getAdminService(), $serviceContainer->getUploadService());
    $myListHandler = MyListHandler::getInstance($serviceContainer->getMyListService());
    $myListCheckHandler = CheckContentMyListHandler::getInstance($serviceContainer->getMyListService());
} catch (Exception $e) {
    Logger::getInstance()->logMessage('Fail to load services '. $e->getMessage());
    exit();
}


/**
 * Making new router instance
 */
$router = new Router();


/**
 * Registering the page routes
 */
$router->addPage('/', function () {
    redirect('index');
});

$router->addPage('/login', function () {
    redirect('login');
});

$router->addPage('/register', function ($urlParams) {
    redirect('register', ['urlParams' => $urlParams]);
});

$router->addPage('/dashboard', function () {
    redirect('dashboard');
}, [LoggedInCheck::getInstance()]);

$router->addPage('/mylist', function () {
    redirect('mylist');
}, [LoggedInCheck::getInstance()]);

$router->addPage('/subscribe', function () {
    redirect('subscribe');
});

$router->addPage('/activate/subscription', function () {
    redirect('activate_subscription');
}, [NotSubscribedCheck::getInstance()]);

$router->addPage('/premium/creators', function () {
    redirect('list_of_premium_creators');
}, [NotSubscribedCheck::getInstance()]);

$router->addPage('/watch', function ($urlParams) {
    redirect('watch', ['urlParams' => $urlParams]);
}, [LoggedInCheck::getInstance(), SubscribedCheck::getInstance()]);


$router->addPage('/account', function () {
    redirect('account');
}, [LoggedInCheck::getInstance()]);

$router->addPage('/admin', function () {
    redirect('admin');
}, [LoggedInCheck::getInstance(), AdminCheck::getInstance()]);

$router->addPage('/admin/movies', function () {
    redirect('admin-movies');
}, [LoggedInCheck::getInstance(), AdminCheck::getInstance()]);

$router->addPage('/admin/users', function () {
    redirect('admin-users');
}, [LoggedInCheck::getInstance(), AdminCheck::getInstance()]);

$router->addPage('/admin/media/management', function () {
    redirect('admin-media-management');
}, [LoggedInCheck::getInstance(), AdminCheck::getInstance()]);

/**
 * Registering the api routes
 */

$router->addAPI('/api/auth/login', 'POST', $loginHandler);
$router->addAPI('/api/auth/register', 'POST', $registerHandler);
$router->addAPI('/api/auth/logout', 'POST', $logoutHandler, [APILoggedInCheck::getInstance()]);

$router->addAPI('/api/users', 'GET', $userHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/users', 'DELETE', $userHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/users', 'PUT', $userHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/content', 'GET', $contentHandler, [LoggedInCheck::getInstance()]);
$router->addAPI('/api/content', 'POST', $contentHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content', 'PUT', $contentHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content', 'DELETE', $contentHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/content/actor', 'GET', $contentActorHandler, [LoggedInCheck::getInstance()]);
$router->addAPI('/api/content/actor', 'POST', $contentActorHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content/actor', 'DELETE', $contentActorHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/content/category', 'GET', $contentCategoryHandler, [LoggedInCheck::getInstance()]);
$router->addAPI('/api/content/category', 'POST', $contentCategoryHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content/category', 'DELETE', $contentCategoryHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/content/director', 'GET', $contentDirectorHandler, [LoggedInCheck::getInstance()]);
$router->addAPI('/api/content/director', 'POST', $contentDirectorHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content/director', 'DELETE', $contentDirectorHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/content/genre', 'GET', $contentGenreHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/content/genre', 'POST', $contentGenreHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/content/genre', 'DELETE', $contentGenreHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/genre', 'GET', $genreHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/genre', 'POST', $genreHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/genre', 'PUT', $genreHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/genre', 'DELETE', $genreHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/upload', 'POST', $uploadHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/category', 'GET', $categoryHandler, [LoggedInCheck::getInstance()]);
$router->addAPI('/api/category', 'POST', $categoryHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/category', 'PUT', $categoryHandler, [APIAdminCheck::getInstance()]);
$router->addAPI('/api/category', 'DELETE', $categoryHandler, [APIAdminCheck::getInstance()]);

$router->addAPI('/api/account/user', 'PUT', $accountHandler, [APILoggedInCheck::getInstance()]);

$router->addAPI('/api/avatar/user', 'POST', $avatarHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/avatar/user', 'GET', $avatarHandler, [APILoggedInCheck::getInstance()]);


$router->addAPI('/api/mylist', 'GET', $myListHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/mylist', 'POST', $myListHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/mylist', 'DELETE', $myListHandler, [APILoggedInCheck::getInstance()]);
$router->addAPI('/api/mylist/check', 'GET', $myListCheckHandler, [APILoggedInCheck::getInstance()]);

/**
 * Setting api or page fallback handler
 */

$router->setPageNotFoundHandler(function () {
    redirect('404');
});

$router->setApiNotFoundHandler(APINotFoundHandler::getInstance());

$router->run();