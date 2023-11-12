let genreData = {};
let currentContentId = null;
// const actorData = {};
// const categoryData = [];
// const directorData = [];

const editGenreDropdown = document.getElementById("edit_genre_id");
const editGenreButton = document.getElementById("edit_genre_button");
const deleteGenreButton = document.getElementById("delete_genre_button");
const editGenreModal = document.getElementById("editGenreModal");
const closeButtons = document.querySelectorAll(".close");
const saveEditGenreButton = document.getElementById("saveEditGenreButton");
const addGenreForm = document.getElementById("add-genre-form");
const genreNameInput = document.getElementById("input-genre");
const findContentGenreForm = document.getElementById("find-content-genre");
const contentIdInput = document.getElementById("content-id");
const contentNameSelect = document.getElementById("content-name");
const contentGenreTable = document.getElementById("content-genre-table");
const contentNewGenreDropdown = document.getElementById(
    "content-new-genre-dropdown"
);
const addNewContentGenreForm = document.getElementById("add-new-content-genre");

let selectedGenreIdToEdit = null;

async function fetchGenreOptions() {
    try {
        const httpClient = new HttpClient();
        const genreResponse = await httpClient.get("/api/genre", null, false);
        const genreJson = JSON.parse(genreResponse.body);

        if (genreJson.data && genreJson.data.length > 0) {
            genreData = {};
            editGenreDropdown.innerHTML = "";

            genreJson.data.forEach((genre) => {
                genreData[genre.genre_id] = genre.genre_name;
            });

            for (genre_id in genreData) {
                editGenreDropdown.innerHTML += `<option value="${genre_id}">${genreData[genre_id]}</option>`;
            }

            selectedGenreIdToEdit =
                editGenreDropdown.options[editGenreDropdown.selectedIndex].value;
        }

    } catch (error) {
        console.error("An error occurred during fetch data:", error);
        alert(`An error occurred during fetch data: ${error.message}`);
    }
}


async function addNewGenre() {
    const genre_name = genreNameInput.value;

    const data = {
        genre_name,
    };

    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post("/api/genre", data, false);
        const json = JSON.parse(response.body);

        if (json.success) {
            alert("Success adding new genre!");
        } else {
            alert("Failed to add genre: " + json.message);
        }
    } catch (error) {
        console.error("An error occurred:", error);
        alert(
            `An error occurred while processing your request: ${error.message}`
        );
    }
}

async function editGenre(newGenreName) {
    const updatedGenreData = {
        genre_id: selectedGenreIdToEdit,
        genre_name: newGenreName,
    };
    try {
        const httpClient = new HttpClient();
        const response = await httpClient.put(
            `/api/genre`,
            updatedGenreData,
            false
        );
        const json = JSON.parse(response.body);

        if (json.success) {
            alert("Edit genre successful");
            await fetchGenreOptions()
            window.location.reload();
        } else {
            console.error("Edit genre failed:", json.error);
            alert("Edit operation failed: " + json.error);
        }
    } catch (error) {
        console.error("An error occurred during editing:", error);
        alert("An error occurred during editing.");
    }
}

async function deleteGenre(genreIdToDelete) {
    const httpClient = new HttpClient();
    const response = await httpClient.delete(
        `/api/genre?genre_id=${genreIdToDelete}`,
        null,
        false
    );

    const responseJson = JSON.parse(response.body);
    if (!responseJson.success) {
        alert(`Failed to delete content genre: ${responseJson.message}`);
        return;
    }

    alert("Genre deleted successfully");
    await fetchGenreOptions();
    window.location.reload();
}

function resetTable(table) {
    const rows = table.getElementsByTagName("tr");

    for (let i = rows.length - 1; i > 0; i--) {
        contentGenreTable.deleteRow(i);
    }
}

async function fetchContentGenreData() {
    const httpClient = new HttpClient();
    const response = await httpClient.get(
        `/api/content/genre?content_id=${currentContentId}`,
        null,
        false
    );

    const responseJson = JSON.parse(response.body);
    if (!responseJson.success) {
        alert(`Failed to fetch content genre: ${responseJson.message}`);
        return false;
    }

    const contentGenreData = responseJson.data;
    for (const genre of contentGenreData) {
        const row = contentGenreTable.insertRow();
        row.setAttribute("data-genre-id", genre.genre_id);
        row.setAttribute("data-content-id", genre.genre_name);
        row.innerHTML = `
            <td>${genre.genre_id}</td>
            <td>${genre.genre_name}</td>
            <td>
                <button class="delete-button">Delete</button>
            </td>
        `;
    }

    contentNewGenreDropdown.innerHTML = "";
    for (const genre_id in genreData) {
        const genreAlreadyUsed = contentGenreData.some(
            (item) => item.genre_id == genre_id
        );
        if (!genreAlreadyUsed) {
            contentNewGenreDropdown.innerHTML += `
                <option value=${genre_id}>${genreData[genre_id]}</option>
            `;
        }
    }

    return true;
}

async function deleteContentGenre(genre_id) {
    const content_id = currentContentId;
    const httpClient = new HttpClient();
    const response = await httpClient.delete("/api/content/genre", {
        genre_id,
        content_id,
    });
    const responseJson = JSON.parse(response.body);
    if (!responseJson.success) {
        alert(`Failed to delete content genre: ${responseJson.message}`);
        return;
    }
}

async function addContentGenre(new_genre_id) {
    const content_id = currentContentId;
    const httpClient = new HttpClient();
    const response = await httpClient.post("/api/content/genre", {
        genre_id: new_genre_id,
        content_id: content_id,
    });
    const responseJson = JSON.parse(response.body);
    if (!responseJson.success) {
        alert(`Failed to add content genre: ${responseJson.message}`);
        return;
    }
}

editGenreButton.addEventListener("click", () => {
    editGenreModal.style.display = "block";
});

window.addEventListener("click", (event) => {
    if (event.target === editGenreModal) {
        editGenreModal.style.display = "none";
    }
});

closeButtons.forEach((button) => {
    button.addEventListener("click", () => {
        editGenreModal.style.display = "none";
    });
});

deleteGenreButton.addEventListener("click", async (event) => {
    const genreIdToDelete = editGenreDropdown.value;
    const confirmDelete = confirm("Are you sure you want to delete this item?");
    if (confirmDelete) {
        await deleteGenre(genreIdToDelete);
    }
});

saveEditGenreButton.addEventListener("click", (event) => {
    event.preventDefault();
    const newGenreName = document.getElementById("editGenreName").value;
    editGenre(newGenreName);
});

addGenreForm.addEventListener("submit", (event) => {
    addNewGenre();
});

findContentGenreForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    resetTable(contentGenreTable);
    currentContentId = contentIdInput.value;
    if (await fetchContentGenreData()) {
        alert("Content genre fetched successfully");
    }
});

document.addEventListener("click", async (event) => {
    if (event.target.classList.contains("delete-button")) {
        const genreIdToDelete =
            event.target.parentElement.parentElement.dataset["genreId"];
        const confirmDelete = confirm("Are you sure you want to delete this item?");
        if (confirmDelete) {
            deleteContentGenre(genreIdToDelete);
            alert("Content genre deleted successfully");
            resetTable(contentGenreTable);
            await fetchContentGenreData();
        }
    }
});

addNewContentGenreForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const new_genre_id = contentNewGenreDropdown.value;
    addContentGenre(new_genre_id);
    resetTable(contentGenreTable);
    await fetchContentGenreData();
});

const pollingInterval = 30000;
fetchGenreOptions();
setInterval(fetchGenreOptions, pollingInterval);
