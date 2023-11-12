const getStartedButton = document.getElementById('input-submit');
getStartedButton.addEventListener('click', function() {
    const inputUsername = document.getElementById('input-username').value;
    if (inputUsername === '' || inputUsername == null) {
        window.location.href = '/register';
    } else {
        window.location.href = '/register?username=' + encodeURIComponent(inputUsername);
    }
});