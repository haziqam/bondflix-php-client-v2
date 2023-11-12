async function submitLogin(e) {
    e.preventDefault();

    const username = document.getElementById('input-username').value;
    const password = document.getElementById('input-password').value;

    const data = {
        username,
        password,
    };

    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post('/api/auth/login', data, false);
        const json = JSON.parse(response.body);
        if (json.success) {
            if (json.data.is_admin === true) {
                window.location.href = "/admin";
            } else {
                window.location.href = "/dashboard";
            }
        } else {
            alert("Login Failed: " + json.message);
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert("An error occurred while processing your request.");
    }
}
