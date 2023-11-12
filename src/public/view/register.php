<?php
$pageTitle = 'Register Page';
include BASE_PATH . "/public/templates/header.php";

$urlParams = $data['urlParams'];
$username = $urlParams['username'] ?? '';

if (isset($_SESSION["user_id"]) && !$_SESSION["is_admin"]) {
    href('/dashboard');
}
?>


<link rel="stylesheet" href="/public/css/register.css">
<script src="/public/js/register.js"></script>
<body>
<nav class="navbar">
    <a href="/"><img src="/public/logo.png" alt="Bonflix Logo" class="logo"></a>
</nav>
<div class="registration-container">
    <h1>User Registration</h1>
    <form id="registration-form">
        <input type="text" id="input-username" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
        <input type="text" id="input-first-name" name="first_name" placeholder="First name" required>
        <input type="text" id="input-last-name" name="last_name" placeholder="Last name" required>
        <input type="password" id="input-password" name="password" placeholder="Password" required>
        <input type="password" id="input-password-confirmation" placeholder="Password confirmation" required>
        <input type="submit" class="submit-button" value="Register">
    </form>
    <a class="login-link" href="/login">Already have an account?</a>
</div>
<script>
    document.getElementById('registration-form').addEventListener('submit', submitRegister);
</script>
</body>