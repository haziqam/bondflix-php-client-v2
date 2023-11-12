<?php

if (!isset($_SESSION['user_id'])) {
    href('/login');
}

if (!isset($urlParams['id'])){
    href('/404');
}

$userID = $_SESSION['user_id'];
$username = $_SESSION['username'];
$isAdmin = $_SESSION['is_admin'];
$isSubscribed = $_SESSION['is_subscribed'];
$pageTitle = 'Watch Movie';
include BASE_PATH . "/public/templates/header.php";
?>

<link rel="stylesheet" href="/public/css/dashboard.css">
<link rel="stylesheet" href="/public/css/watch.css">
<body>
    <?php include BASE_PATH . '/public/templates/navbar.php' ?>
    <div class="container">
        <div id="most-recommended-wrapper">
            <div class="stream-container">
                <div class="video-wrapper">
                    <video controls autoplay id="video-element" muted="muted">
                        <source src="" type="video/mp4" id="video-source">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-info">
                    <div class="title-button-container">
                            <h1 id="movie-title"></h1>
                        <div class="button-container">
                            <button id="add-button" class="add-delete-button"></button>
                            <button id="delete-button" class="add-delete-button"></button>
                        </div>
                    </div>
                    <div id="genre-container" style="display: flex; flex-direction: row">
                    </div>
                    <p id="movie-release-date"></p>
                    <p id="movie-description"></p>

                </div>
            </div>
        </div>
        <div class="more-recommendation">
            <h2>Movies</h2>
            <div class="recommendations-content" id="search-result-container">
            </div>
        </div>
        <div class="pagination">
            <button id="prevPageButton">◄</button>
            <button id="currentPageButton">1</button>
            <button id="nextPageButton">►</button>
        </div>
    </div>
    <script src="/public/js/dashboard.js"></script>
    <script> const userId = <?php echo $userID ?>;</script>
    <script src="/public/js/watch.js"></script>
</body>