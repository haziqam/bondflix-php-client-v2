const Elements = {
    editProfilePictureButton: document.getElementById("edit-profile-pic-button"),
    saveButton: document.getElementById("save-button"),
    cancelButton: document.getElementById("cancel-button"),
    firstNameInput: document.getElementById("first-name"),
    lastNameInput: document.getElementById("last-name"),
    passwordInput: document.getElementById("password"),
    profilePicture: document.getElementById("profile-picture"),
    profilePictureInput: document.getElementById("profile-picture-input")
};

function initEventListeners()
{
    Elements.editProfilePictureButton.addEventListener("click", () => {
        updateProfilePicture();
    })

    Elements.saveButton.addEventListener("click", () => {
        updateProfile();
    })

    Elements.cancelButton.addEventListener("click", () => {
        Elements.firstNameInput.value = '';
        Elements.lastNameInput.value = '';
        Elements.passwordInput.value = '';
    })

    Elements.profilePictureInput.addEventListener('change', () => {
        if (Elements.profilePictureInput.files.length > 0) {
            const selectedFile = Elements.profilePictureInput.files[0];

            Elements.profilePicture.src = URL.createObjectURL(selectedFile);
        } else {
            Elements.profilePicture.src = '/public/avatar.png';
        }
    })
}

async function updateProfilePicture(){

    if (Elements.profilePictureInput.files.length > 0) {
        const selectedFile = Elements.profilePictureInput.files[0];
        try {
            const httpClient = new HttpClient();
            const response = await httpClient.uploadFile(
                '/api/avatar/user',
                selectedFile,
                false
            );
            const json = JSON.parse(response);
            if (json.success) {
                alert("Update profile picture successful!");
                window.location.reload();
            } else {
                alert("Profile picture update failed: " + json.message);
            }
        } catch (error) {
            console.error("An error occurred:", error);
            alert("An error occurred while processing your request.");
        }
    } else {
        alert("Please select an image to update your profile picture.");
    }
}

async function updateProfile()
{

    const first_name = Elements.firstNameInput.value;
    const last_name = Elements.lastNameInput.value;
    const password = Elements.passwordInput.value;

    if (first_name === "") {
        alert("Please enter your first name.");
        return;
    }

    if (password !== '' && password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return;
    }
    const data = {
        first_name,
        last_name,
        password,
    }
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.put(
            '/api/account/user',
            data,
            false
        );

        const json = JSON.parse(response.body);
        if (json.success) {
            alert("Update successful!");
            window.location.reload();
        } else {
            alert("Registration failed: " + json.message)
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert("An error occurred while processing your request.");
    }
}

async function fetchAndSetProfilePicture() {
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.get('/api/avatar/user', null, false);
        const json = JSON.parse(response.body);
        if (json.success && json.data !== '' && json.data !== null) {
            Elements.profilePicture.src = '/uploads/avatars/' + json.data;
        } else {
            Elements.profilePicture.src = '/public/avatar.png';
        }
    } catch (error) {
        console.error('Error fetching profile picture:', error);
    }
}


initEventListeners();
fetchAndSetProfilePicture();
