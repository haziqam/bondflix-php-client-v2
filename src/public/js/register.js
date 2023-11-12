async function submitRegister(e) {
    e.preventDefault();

    const username = document.getElementById('input-username').value;
    const first_name = document.getElementById('input-first-name').value;
    const last_name = document.getElementById('input-last-name').value;
    const password = document.getElementById('input-password').value;
    const password_confirmation = document.getElementById('input-password-confirmation').value;

    if (username === '') {
        alert("Please enter a username.");
        return;
    }

    if (first_name === '') {
        alert("Please enter your first name.");
        return;
    }

    if (last_name === '') {
        alert("Please enter your last name.");
        return;
    }

    if (password === '') {
        alert("Please enter a password.");
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return;
    }

    if (password !== password_confirmation) {
        alert("Password do not match!")
        return;
    }

    const data = {
        username,
        first_name,
        last_name,
        password,
        password_confirmation
    };

    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post('/api/auth/register', data, false);
        const json = JSON.parse(response.body);
        if (json.success) {
            alert("Registration successful!");
            window.location.href = "/login";
        } else {
            alert("Registration failed: " + json.message);
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert("An error occurred while processing your request.");
    }
}
