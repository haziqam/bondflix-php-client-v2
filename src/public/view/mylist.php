<?php

if (!isset($_SESSION['user_id'])) {
    href('/login');
}

$userID = $_SESSION['user_id'];
$username = $_SESSION['username'];
$isAdmin = $_SESSION['is_admin'];
$isSubscribed = $_SESSION['is_subscribed'];
$pageTitle = 'My List';
include BASE_PATH . "/public/templates/header.php";
?>

<link rel="stylesheet" href="/public/css/dashboard.css">
<link rel="stylesheet" href="/public/css/mylist.css">
<body>
<?php include BASE_PATH . '/public/templates/navbar.php' ?>
<div class="container">
    <div id="most-recommended-wrapper">
        <div class="more-recommendation">
            <h2>My Movies</h2>
            <div class="recommendations-content" id="search-result-mylist-container">
            </div>
        </div>
    </div>
    <div class="pagination">
        <button id="prevPageButton">◄</button>
        <button id="currentPageButton">1</button>
        <button id="nextPageButton">►</button>
    </div>
</div>
<script src="/public/js/dashboard.js"></script>
<script>const userId = <?php echo $userID; ?>;</script>
<script src="/public/js/mylist.js"></script>
</body>