<?php
$pageTitle = 'Activation Page';
include BASE_PATH . "/public/templates/header.php";
?>

<link rel="stylesheet" href="/public/css/activate_subscription.css">
<body>
<nav class="navbar">
    <a href="/"><img src="/public/logo.png" alt="Bonflix Logo" class="logo"></a>
</nav>
<div class="centered-content">
    <div class="container">
        <div class="money-input-container">
            <label for="amount">Enter Amount:</label>
            <input type="number" id="amount" name="amount" min="0" placeholder="Enter amount">
            <button id="send-button" class="submit-button">Send</button>
        </div>
    </div>
</div>
<script src="/public/js/activate_subscription.js"></script>
</body>
