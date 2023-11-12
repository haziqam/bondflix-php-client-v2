<?php
$pageTitle = 'Login';
include BASE_PATH . "/public/templates/header.php";

if (isset($_SESSION["user_id"])) {
    if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
        href('/admin');
    } else {
        href('/dashboard');
    }
}
?>

<link rel="stylesheet" href="/public/css/login.css">
<script src="/public/js/login.js"></script>
<body>
<!--<div id="loading-overlay">-->
<!--    <div class="spinner"></div>-->
<!--</div>-->
<nav class="navbar">
    <a href="/"><img src="/public/logo.png" alt="Bonflix Logo" class="logo"></a>
</nav>
<div class="login-container">
    <div class="overlay">
        <h1>Sign In</h1>
        <form id="login-form">
            <input type="text" id="input-username" name="username" placeholder="Username" required>
            <input type="password" id="input-password" name="password" placeholder="Password" required>
            <input type="submit" class="submit-button" value="Login">
        </form>
        <a class="register-link" href="/register">Haven't registered yet?</a>
    </div>
</div>
<script>
    document.getElementById('login-form').addEventListener('submit', submitLogin);
</script>
</body>
