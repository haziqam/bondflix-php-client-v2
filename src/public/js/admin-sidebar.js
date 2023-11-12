async function logout() {
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post('/api/auth/logout', null, false);
        const json = JSON.parse(response.body);
        if (json.success) {
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "/";
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert("An error occurred while processing your request.");
    }
}
