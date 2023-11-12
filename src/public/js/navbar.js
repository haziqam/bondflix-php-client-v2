const navbarAccountButton = document.querySelector(".navbar-account-button");
const accountMenu = document.querySelector(".account-menu");
const profilePicture = document.getElementById("profile-picture-navbar");
const hamburgerButton = document.querySelector(".hamburger-button");
const accountMenuForPhone = document.querySelectorAll(
    ".navbar .account-menu-for-phone"
);

navbarAccountButton.addEventListener("click", () => {
    if (accountMenu.style.display === "block") {
        accountMenu.style.display = "none";
    } else {
        accountMenu.style.display = "block";
    }
});

hamburgerButton.addEventListener("click", (event) => {
    if (accountMenu.style.display === "block") {
        accountMenu.style.display = "none";
        accountMenuForPhone.forEach((element) => {
            element.style.display = "none";
        });
    } else {
        accountMenu.style.display = "block";
        accountMenuForPhone.forEach((element) => {
            element.style.display = "block";
        });
    }
});

async function logout() {
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post("/api/auth/logout", null, false);
        const json = JSON.parse(response.body);
        if (json.success) {
            document.cookie =
                "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "/";
        } else {
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert("An error occurred while processing your request.");
    }
}

function updateGenres(genres){
    const genreDropdown = document.getElementById('genre-dropdown');
    genreDropdown.innerHTML = '';

    const allGenresOption = document.createElement('option');
    allGenresOption.value = 'all';
    allGenresOption.textContent = 'All Genres';
    genreDropdown.appendChild(allGenresOption);

    for (const genre of genres) {
        const option = document.createElement('option');
        option.value = genre.genre_id;
        option.textContent = genre.genre_name;
        genreDropdown.appendChild(option);
    }
}
async function fetchAndSetProfilePicture() {
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.get("/api/avatar/user", null, false);
        const json = JSON.parse(response.body);
        if (json.success && json.data !== "" && json.data !== null) {
            profilePicture.src = "/uploads/avatars/" + json.data;
        } else {
            profilePicture.src = "/public/avatar.png";
        }
    } catch (error) {
        console.error("Error fetching profile picture:", error);
    }
}

async function fetchGenres(){
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.get('/api/content/genre', null, false);
        const json = JSON.parse(response.body);
        if (json.success){
            updateGenres(json.data);
        }
    } catch (error) {
        console.error('Error fetching profile picture:', error);
    }
}

fetchAndSetProfilePicture();
fetchGenres();
