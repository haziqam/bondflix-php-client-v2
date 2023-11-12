<?php
    $pageTitle = 'Landing Page';
    include BASE_PATH . "/public/templates/header.php";

    if (isset($_SESSION["user_id"])){
        href('/dashboard');
    }
?>

<link rel="stylesheet" href="/public/css/index.css">
<body>
<nav class="navbar">
    <a href="/"><img src="/public/logo.png" alt="Bonflix Logo" class="logo"></a>
    <a href="/login" class="submit-button" id="sign-in-button">Sign In</a>
</nav>
<div class="centered-content">
    <div class="container">
        <h1>The biggest local and international hits. The best stories. All streaming here.</h1>
        <h2>Watch anywhere. Cancel anytime.</h2>
        <h3>Ready to watch? Enter your email to create or restart your membership.</h3>
    </div>
    <div class="register-container">
        <input type="text" id="input-username" name="username" placeholder="Email address or username">
        <input type="submit" id="input-submit" class="submit-button" value="Get Started 〉">
    </div>
</div>
<script src="/public/js/index.js"></script>
</body>