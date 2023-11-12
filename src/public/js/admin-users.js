const helper = new Helper();
const UserTable = {
    data: {},
    currentUserId: null,
    currentPage: 1,
    totalPages: 1,
    isAscending: true,
    isAdmin: true,
    isSubscribed: true,
    filterEnabled: false,
    pageSize: 10,
};
let debounceTimer;

const Elements = {
    searchInput: document.getElementById("search-input"),
    sortButton: document.getElementById("sort-button"),
    isAdminButton: document.getElementById("admin-filter-button"),
    isSubscribedButton: document.getElementById("sub-filter-button"),
    filterEnableButton: document.getElementById("enable-filter-button"),
    prevPageButton: document.getElementById("prevPageButton"),
    nextPageButton: document.getElementById("nextPageButton"),
    newUserModal: document.getElementById("newUserModal"),
    openUserModalButton: document.getElementById("add-user-button"),
    closeUserModalButton: document.getElementById("close-user"),
    submitUserButton: document.getElementById("newUserButton"),
    editUserModal: document.getElementById("editUserModal"),
    editUsernameInput: document.getElementById("editUsername"),
    editFirstNameInput: document.getElementById("editFirstName"),
    editLastNameInput: document.getElementById("editLastName"),
    passwordInput: document.getElementById("editPassword"),
    editAdminSelect: document.getElementById("editStatusAdmin"),
    editSubscriptionSelect: document.getElementById("editStatusSubscription"),
    closeEditModalButton: document.getElementById("close-edit"),
    saveEditButton: document.getElementById("saveEditButton"),
    currentPageButton: document.getElementById("currentPageButton"),
};

const Constants = {
    BUTTON_TEXT: {
        ENABLED: "✓",
        DISABLED: "✗",
    },
    BUTTON_CLASSES: {
        ENABLED: "green-status",
        DISABLED: "red-status",
    },
};

function updateTable(users) {
    const tableBody = document.querySelector("table tbody");
    tableBody.innerHTML = "";

    if (users && users.length > 0) {
        users.forEach((user) => {
            const row = tableBody.insertRow();
            row.setAttribute("data-user-id", user.user_id);

            const adminStatus = user.is_admin
                ? Constants.BUTTON_TEXT.ENABLED
                : Constants.BUTTON_TEXT.DISABLED;
            const subscriptionStatus = user.is_subscribed
                ? Constants.BUTTON_TEXT.ENABLED
                : Constants.BUTTON_TEXT.DISABLED;
            const adminStatusClass = user.is_admin
                ? Constants.BUTTON_CLASSES.ENABLED
                : Constants.BUTTON_CLASSES.DISABLED;
            const subscriptionStatusClass = user.is_subscribed
                ? Constants.BUTTON_CLASSES.ENABLED
                : Constants.BUTTON_CLASSES.DISABLED;

            row.innerHTML = `
        <td>${user.user_id}</td>
        <td>${user.username}</td>
        <td>${user.first_name}</td>
        <td>${user.last_name}</td>
        <td class="${adminStatusClass}">${adminStatus}</td>
        <td class="${subscriptionStatusClass}">${subscriptionStatus}</td>
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

    UserTable.data = users;
}

function handlePaginationButtons() {
    Elements.currentPageButton.innerHTML = UserTable.currentPage;
    Elements.prevPageButton.disabled = UserTable.currentPage === 1;
    Elements.nextPageButton.disabled =
        UserTable.currentPage === UserTable.totalPages || UserTable.totalPages === 0;
}

async function fetchData() {
    try {
        const httpClient = new HttpClient();
        const query = Elements.searchInput.value.trim();
        let url = `/api/users?query=${query}&sortAscending=${UserTable.isAscending}&page=${UserTable.currentPage}&pageSize=${UserTable.pageSize}`;

        if (UserTable.filterEnabled) {
            url += `&isAdmin=${UserTable.isAdmin}&isSubscribed=${UserTable.isSubscribed}`;
        }
        const response = await httpClient.get(url, null, false);

        const data = JSON.parse(response.body).data;
        UserTable.totalPages = parseInt(response.headers["x-total-pages"]);
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
        UserTable.isAscending = !UserTable.isAscending;
        Elements.sortButton.textContent = `Sort ID ${
            UserTable.isAscending ? "↑" : "↓"
        }`;
        fetchData();
    });

    Elements.isAdminButton.addEventListener("click", () => {
        UserTable.isAdmin = !UserTable.isAdmin;
        const buttonText = UserTable.isAdmin
            ? Constants.BUTTON_TEXT.ENABLED
            : Constants.BUTTON_TEXT.DISABLED;
        Elements.isAdminButton.textContent = `Is Admin ${buttonText}`;
        Elements.isAdminButton.classList.toggle(
            "green-status",
            UserTable.isAdmin
        );
        Elements.isAdminButton.classList.toggle(
            "red-status",
            !UserTable.isAdmin
        );
        Elements.isAdminButton.style.backgroundColor = UserTable.isAdmin
            ? "green"
            : "#e50914";
        fetchData();
    });

    Elements.isSubscribedButton.addEventListener("click", () => {
        UserTable.isSubscribed = !UserTable.isSubscribed;
        const buttonText = UserTable.isSubscribed
            ? Constants.BUTTON_TEXT.ENABLED
            : Constants.BUTTON_TEXT.DISABLED;
        Elements.isSubscribedButton.textContent = `Is Subscribed ${buttonText}`;
        Elements.isSubscribedButton.classList.toggle(
            "green-status",
            UserTable.isSubscribed
        );
        Elements.isSubscribedButton.classList.toggle(
            "red-status",
            !UserTable.isSubscribed
        );
        Elements.isSubscribedButton.style.backgroundColor =
            UserTable.isSubscribed ? "green" : "#e50914";
        fetchData();
    });

    Elements.filterEnableButton.addEventListener("click", () => {
        UserTable.filterEnabled = !UserTable.filterEnabled;
        Elements.filterEnableButton.textContent = `Filter ${
            UserTable.filterEnabled ? "Enabled ✓" : "Disabled ✗"
        }`;
        Elements.filterEnableButton.classList.toggle(
            "green-status",
            UserTable.filterEnabled
        );
        Elements.filterEnableButton.classList.toggle(
            "red-status",
            !UserTable.filterEnabled
        );
        Elements.filterEnableButton.style.backgroundColor =
            UserTable.filterEnabled ? "green" : "#e50914";
        fetchData();
    });

    Elements.prevPageButton.addEventListener("click", () => {
        if (UserTable.currentPage > 1) {
            UserTable.currentPage--;
            fetchData();
        }
    });

    Elements.nextPageButton.addEventListener("click", () => {
        if (UserTable.currentPage < UserTable.totalPages) {
            UserTable.currentPage++;
            fetchData();
        }
    });

    helper.openModal(
        Elements.newUserModal,
        Elements.openUserModalButton,
        Elements.closeUserModalButton,
        Elements.submitUserButton,
        onSubmitUserModal
    );

    Elements.closeEditModalButton.addEventListener("click", () => {
        Elements.editUserModal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === Elements.editUserModal) {
            Elements.editUserModal.style.display = "none";
        }
        if (event.target === Elements.newUserModal) {
            Elements.newUserModal.style.display = "none";
        }
    });

    document.addEventListener("click", async (event) => {
        const target = event.target;

        if (
            target.classList.contains("delete-button") ||
            target.id === "delete-button"
        ) {
            const userId = target.closest("tr").getAttribute("data-user-id");

            const confirmDelete = window.confirm("Are you sure you want to delete this user?");

            if (confirmDelete) {
                try {
                    const httpClient = new HttpClient();
                    const response = await httpClient.delete(
                        `/api/users?userId=${userId}`
                    );
                    const json = JSON.parse(response.body);
                    if (json.success) {
                        alert("Delete operation successful");
                        location.reload();
                    } else {
                        console.error("Delete operation failed:", response.error);
                        alert("Delete operation failed." + response.error);
                    }
                } catch (error) {
                    console.error("An error occurred during deletion:", error);
                    alert("An error occurred during deletion.");
                }
            }
        }
    });

    document.addEventListener("click", async (event) => {
        const target = event.target;

        if (target.classList.contains("edit-button")) {
            const userId = target.closest("tr").getAttribute("data-user-id");
            const user = UserTable.data.find(
                (user) => user.user_id === parseInt(userId)
            );

            UserTable.currentUserId = userId;

            const username = user.username;
            const firstName = user.first_name;
            const lastName = user.last_name;
            const isAdmin = user.is_admin;
            const isSubscribed = user.is_subscribed;

            Elements.editUsernameInput.value = username;
            Elements.editFirstNameInput.value = firstName;
            Elements.editLastNameInput.value = lastName;

            Elements.editAdminSelect.value = isAdmin.toString();
            Elements.editSubscriptionSelect.value = isSubscribed.toString();
            Elements.editUserModal.style.display = "block";
        }

        if (target.classList.contains("close")) {
            Elements.editUserModal.style.display = "none";
        }
    });

    Elements.saveEditButton.addEventListener("click", async (e) => {
        e.preventDefault();

        const userId = UserTable.currentUserId;
        const username = Elements.editUsernameInput.value;
        const first_name = Elements.editFirstNameInput.value;
        const last_name = Elements.editLastNameInput.value;
        const password = Elements.passwordInput.value;
        const is_admin = Elements.editAdminSelect.value === "true"; // Convert to boolean
        const is_subscribed = Elements.editSubscriptionSelect.value === "true"; // Convert to boolean

        if (password !== '' && password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return;
        }

        let updatedUserData = {
            userId,
            username: username,
            first_name: first_name,
            last_name: last_name,
            password: password,
            is_admin: is_admin,
            is_subscribed: is_subscribed,
        };
        
        const confirmEdit = window.confirm("Are you sure you want to edit this user?");

        if (confirmEdit)
        {
            try {
                const httpClient = new HttpClient();
                const response = await httpClient.put(
                    `/api/users?userId=${userId}`,
                    updatedUserData,
                    false
                );

                console.log(response.body)

                const json = JSON.parse(response.body);
                if (json.success) {
                    alert("Edit operation successful");
                    location.reload();
                } else {
                    console.error("Edit operation failed:", json.error);
                    alert("Edit operation failed: " + json.error);
                }
            } catch (e) {
                console.error("Edit operation failed");
                alert("Edit operation failed");
            }
        }
    });
}

async function onSubmitUserModal(modal) {
    const username = modal.querySelector("#newUsername").value;
    const first_name = modal.querySelector("#newFirstName").value;
    const last_name = modal.querySelector("#newLastName").value;
    const password = modal.querySelector("#newPassword").value;
    const password_confirmation = modal.querySelector(
        "#newPasswordConfirmation"
    ).value;

    if (username === "") {
        alert("Please enter a username.");
        return;
    }

    if (first_name === "") {
        alert("Please enter your first name.");
        return;
    }

    if (last_name === "") {
        alert("Please enter your last name.");
        return;
    }

    if (password === "") {
        alert("Please enter a password.");
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return;
    }

    if (password !== password_confirmation) {
        alert("Password do not match!");
        return;
    }

    const data = {
        username,
        first_name,
        last_name,
        password,
        password_confirmation,
    };

    try {
        const httpClient = new HttpClient();
        const response = await httpClient.post(
            "/api/auth/register",
            data,
            false
        );
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
    modal.style.display = "none";
}

initEventListeners();

fetchData();
