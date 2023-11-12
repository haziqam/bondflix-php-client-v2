const helper = new Helper();
const ContentTable = {
    data: {},
    currentContentId: null,
    currentPage: 1,
    totalPages: 1,
    isAscending: true,
    filterEnabled: false,
    pageSize: 10,
};
let debounceTimer;

const Elements = {
    /**
     * Search bar input and buttons
     */
    searchInput: document.getElementById("search-input"),
    sortButton: document.getElementById("sort-button"),

    /**
     * Filter part
     */
    filterEnableButton: document.getElementById("enable-filter-button"),

    /**
     * Pagination Part
     */
    prevPageButton: document.getElementById("prevPageButton"),
    nextPageButton: document.getElementById("nextPageButton"),
    currentPageButton: document.getElementById("currentPageButton"),

    /**
     * New Content Modal
     */
    openNewContentModalButton: document.getElementById("add-content-button"),
    newContentModal: document.getElementById("new-content-modal"),
    closeNewContentModalButton: document.getElementById(
        "close-new-content-modal"
    ),
    submitNewContentButton: document.querySelector(
        ".submit-new-content-button"
    ),

    /**
     * Add Content Form
     */
    newContentForm: document.getElementById("new-content-form"),
    newContentTitleInput: document.querySelector(
        "#new-content-form .movie-title"
    ),
    newContentDescriptionInput: document.querySelector(
        "#new-content-form .movie-description"
    ),
    newContentReleaseDateInput: document.querySelector(
        "#new-content-form .movie-release-date"
    ),
    newContentThumbnailInput: document.querySelector(
        "#new-content-form .movie-thumbnail"
    ),
    newContentVideoInput: document.querySelector(
        "#new-content-form .movie-video"
    ),

    /**
     * Edit Content Modal
     */
    editContentModal: document.getElementById("edit-content-modal"),
    closeEditContentModalButton: document.getElementById(
        "close-edit-content-modal"
    ),
    submitEditContentButton: document.querySelector(
        ".submit-edit-content-button"
    ),

    /**
     * Edit Content Form
     */
    editContentForm: document.getElementById("edit-content-form"),
    editContentTitleInput: document.querySelector(
        "#edit-content-form .movie-title"
    ),
    editContentDescriptionInput: document.querySelector(
        "#edit-content-form .movie-description"
    ),
    editContentReleaseDateInput: document.querySelector(
        "#edit-content-form .movie-release-date"
    ),
    editContentThumbnailInput: document.querySelector(
        "#edit-content-form .movie-thumbnail"
    ),
    editContentVideoInput: document.querySelector(
        "#edit-content-form .movie-video"
    ),
};

function updateTable(contents) {
    const tableBody = document.querySelector("table tbody");
    tableBody.innerHTML = "";

    if (contents && contents.length > 0) {
        contents.forEach((content) => {
            const row = tableBody.insertRow();
            row.setAttribute("data-content-id", content.content_id);

            const contentFilePath = content.content_file_path.replace(
                "/uploads/videos/",
                ""
            );
            const thumbnailFilePath = content.thumbnail_file_path.replace(
                "/uploads/thumbnails/",
                ""
            );

            row.innerHTML = `
            <td>${content.content_id}</td>
            <td>${content.title}</td>
            <td>${content.description}</td>
            <td>${content.release_date}</td>
            <td>${contentFilePath}</td>
            <td>${thumbnailFilePath}</td>
            <td>
              <button class="edit-button">Edit</button>
              <button class="delete-button">Delete</button>
            </td>
          `;
        });
    } else {
        const placeholderRow = tableBody.insertRow();
        placeholderRow.innerHTML = `
            <td colspan="7">No data available</td>
            `;
    }

    ContentTable.data = contents;
}

function handlePaginationButtons() {
    Elements.prevPageButton.disabled = ContentTable.currentPage === 1;
    Elements.currentPageButton.innerHTML = ContentTable.currentPage;
    Elements.nextPageButton.disabled =
        ContentTable.currentPage === ContentTable.totalPages ||
        ContentTable.totalPages === 0;
}

async function fetchData() {
    try {
        const httpClient = new HttpClient();
        const query = Elements.searchInput.value.trim();
        let url = `/api/content?query=${query}&sortAscending=${ContentTable.isAscending}&page=${ContentTable.currentPage}&pageSize=${ContentTable.pageSize}`;

        if (ContentTable.filterEnabled) {
            // url += `&isAdmin=${ContentTable.isAdmin}&isSubscribed=${ContentTable.isSubscribed}`;
        }
        const response = await httpClient.get(url, null, false);

        const data = JSON.parse(response.body).data;
        ContentTable.totalPages = parseInt(response.headers["x-total-pages"]);
        updateTable(data);
        handlePaginationButtons();
    } catch (error) {
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}

function initEventListeners() {
    Elements.searchInput.addEventListener("input", () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchData();
        }, 500);
    });

    Elements.sortButton.addEventListener("click", () => {
        ContentTable.isAscending = !ContentTable.isAscending;
        Elements.sortButton.textContent = `Sort Title ${
            ContentTable.isAscending ? "↑" : "↓"
        }`;
        fetchData();
    });

    Elements.filterEnableButton.addEventListener("click", () => {
        ContentTable.filterEnabled = !ContentTable.filterEnabled;
        Elements.filterEnableButton.textContent = `Filter ${
            ContentTable.filterEnabled ? "Enabled ✓" : "Disabled ✗"
        }`;
        Elements.filterEnableButton.classList.toggle(
            "green-status",
            ContentTable.filterEnabled
        );
        Elements.filterEnableButton.classList.toggle(
            "red-status",
            !ContentTable.filterEnabled
        );
        Elements.filterEnableButton.style.backgroundColor =
            ContentTable.filterEnabled ? "green" : "#e50914";
        fetchData();
    });

    Elements.prevPageButton.addEventListener("click", () => {
        if (ContentTable.currentPage > 1) {
            ContentTable.currentPage--;
            fetchData();
        }
    });

    Elements.nextPageButton.addEventListener("click", () => {
        if (ContentTable.currentPage < ContentTable.totalPages) {
            ContentTable.currentPage++;
            fetchData();
        }
    });

    Elements.openNewContentModalButton.addEventListener("click", () => {
        Elements.newContentModal.style.display = "block";
    });

    Elements.closeNewContentModalButton.addEventListener("click", () => {
        Elements.newContentModal.style.display = "none";
    });

    document.addEventListener("click", async (event) => {
        const target = event.target;

        if (
            target.classList.contains("delete-button") ||
            target.id === "delete-button"
        ) {
            const contentId = target
                .closest("tr")
                .getAttribute("data-content-id");

            const confirmDelete = window.confirm(
                "Are you sure you want to delete this content?"
            );

            if (confirmDelete) {
                try {
                    const httpClient = new HttpClient();
                    const response = await httpClient.delete(
                        `/api/content?contentId=${contentId}`
                    );
                    const json = JSON.parse(response.body);
                    if (json.success) {
                        alert("Delete operation successful");
                        location.reload();
                    } else {
                        console.error(
                            "Delete operation failed:",
                            response.error
                        );
                        alert("Delete operation failed." + response.error);
                    }
                } catch (error) {
                    console.error("An error occurred during deletion:", error);
                    alert("An error occurred during deletion.");
                }
            }
        }
    });

    helper.openModal(
        Elements.newContentModal,
        Elements.openNewContentModalButton,
        Elements.closeNewContentModalButton,
        Elements.submitNewContentButton,
        onSubmitNewContentModal
    );

    window.addEventListener("click", (event) => {
        if (event.target === Elements.editContentModal) {
            Elements.editContentModal.style.display = "none";
        }
        if (event.target === Elements.newContentModal) {
            Elements.newContentModal.style.display = "none";
        }
    });

    Elements.closeEditContentModalButton.addEventListener("click", () => {
        Elements.editContentModal.style.display = "none";
    });

    document.addEventListener("click", (event) => {
        const target = event.target;

        if (target.classList.contains("edit-button")) {
            const contentId = target
                .closest("tr")
                .getAttribute("data-content-id");
            const content = ContentTable.data.find(
                (content) => content.content_id === parseInt(contentId)
            );

            ContentTable.currentContentId = contentId;

            Elements.editContentTitleInput.value = content.title;
            Elements.editContentDescriptionInput.value = content.description;
            Elements.editContentReleaseDateInput.value = content.release_date;

            Elements.editContentModal.style.display = "block";
        }
    });

    Elements.editContentForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        try {
            const httpClient = new HttpClient();
            let updatedVideoFilePath = null;
            let updatedThumbnailFilePath = null;

            const description = Elements.editContentDescriptionInput.value;
            if (description.length > 1023) {
                alert("Description should not exceed 1023 characters.");
                return;
            }

            const updateContentParams = {
                content_id: ContentTable.currentContentId,
                title: Elements.editContentTitleInput.value,
                description: Elements.editContentDescriptionInput.value,
                release_date: Elements.editContentReleaseDateInput.value,
            };

            const updatedVideoInput = Elements.editContentVideoInput;
            if (updatedVideoInput.files[0]) {
                updateContentParams.content_file_path = await uploadFile(
                    httpClient,
                    updatedVideoInput.files[0],
                    "videos"
                );
            }
            const updatedThumbnailInput = Elements.editContentThumbnailInput;
            if (updatedThumbnailInput.files[0]) {
                updateContentParams.thumbnail_file_path = await uploadFile(
                    httpClient,
                    updatedThumbnailInput.files[0],
                    "thumbnails"
                );
            }

            // if (updatedVideoFilePath !== null) {
            //     updateContentParams["content_file_path"] = updatedVideoFilePath;
            // }
            // if (updatedThumbnailFilePath !== null) {
            //     updateContentParams["thumbnail_file_path"] =
            //         updatedThumbnailFilePath;
            // }

            const confirmEdit = window.confirm(
                "Are you sure you want to edit this content?"
            );

            if (confirmEdit) {
                const updateContentResponseData = await updateContent(
                    httpClient,
                    updateContentParams
                );

                if (updateContentResponseData) {
                    alert("Success updating content!");
                    window.location.reload();
                }
            }
        } catch (err) {
            alert(err.message);
            console.error(err);
        } finally {
            Elements.editContentModal.style.display = "none";
        }
    });

    Elements.closeEditContentModalButton.addEventListener("click", (event) => {
        Elements.editContentModal.style.display = "none";
    });
}

/**
 *
 * @param {HttpClient} httpClient
 * @param {*} addContentParams
 * @returns
 */
async function addContent(httpClient, addContentParams) {
    const addContentResponse = await httpClient.post(
        "/api/content",
        addContentParams,
        false
    );

    const addContentResponseBody = JSON.parse(addContentResponse.body);
    if (!addContentResponseBody.success) {
        throw new Error(
            "Failed to add content: " + addContentResponseBody.message
        );
    }

    return addContentResponseBody.data;
}

async function updateContent(httpClient, updateContentParams) {
    const updateContentResponse = await httpClient.put(
        "/api/content",
        updateContentParams,
        false
    );

    const updateContentResponseBody = JSON.parse(updateContentResponse.body);
    if (!updateContentResponseBody.success) {
        throw new Error(
            "Failed to update content: " + updateContentResponseBody.message
        );
    }

    return updateContentResponseBody.data;
}

async function uploadFile(httpClient, file, uploadType) {
    const fileUploadResponse = await httpClient.uploadFile(
        "/api/upload",
        file,
        false
    );

    const fileUploadResponseBody = JSON.parse(fileUploadResponse);
    if (!fileUploadResponseBody.success) {
        throw new Error(fileUploadResponseBody.message);
    }

    return `/uploads/${uploadType}/${fileUploadResponseBody.data.file_name}`;
}

async function onSubmitNewContentModal(modal) {
    try {
        const httpClient = new HttpClient();

        const description = Elements.newContentDescriptionInput.value;
        if (description.length > 1023) {
            alert("Description should not exceed 1023 characters.");
            return;
        }
        const videoFilePath = await uploadFile(
            httpClient,
            Elements.newContentVideoInput.files[0],
            "videos"
        );
        const thumbnailFilePath = await uploadFile(
            httpClient,
            Elements.newContentThumbnailInput.files[0],
            "thumbnails"
        );

        const addContentParams = {
            title: Elements.newContentTitleInput.value,
            description: Elements.newContentDescriptionInput.value,
            release_date: Elements.newContentReleaseDateInput.value,
            content_file_path: videoFilePath,
            thumbnail_file_path: thumbnailFilePath,
        };
        const addContentResponseData = await addContent(
            httpClient,
            addContentParams
        );

        if (addContentResponseData) {
            alert("Success adding new content!");
            window.location.reload();
        }
    } catch (err) {
        alert(err.message);
        console.error(err);
    } finally {
        modal.style.display = "none";
    }
}

initEventListeners();

fetchData();
