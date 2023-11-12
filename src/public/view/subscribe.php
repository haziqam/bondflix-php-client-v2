<?php


if (isset($_SESSION['is_subscribed']) && $_SESSION['is_subscribed'] === true) {
    href('/dashboard');
}
$pageTitle = 'Not Subscribed';
include BASE_PATH . "/public/templates/header.php";
?>

<link rel="stylesheet" href="/public/css/subscribe.css">
<body>
<nav class="navbar">
    <a href="/"><img src="/public/logo.png" alt="Bondflix Logo" class="logo"></a>
</nav>
<div class="centered-content">
    <div class="container">
        <h1>Sorry</h1>
        <h2>You can't access the content because</h2>
        <h3>You are not subscribed.</h3>
    </div>
    <a href="/" id="back-button" class="submit-button">Bondflix Home Page</a>
</div>
<script src="/public/js/index.js"></script>
</body>