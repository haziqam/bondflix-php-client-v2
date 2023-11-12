<?php
$pageTitle = 'User Dashboard';
$adminSidebarTemplate = BASE_PATH . "/public/templates/admin-sidebar.php";
$username = $_SESSION['username'];
include BASE_PATH . "/public/templates/header.php";
?>

<link rel="stylesheet" href="/public/css/admin-page.css">
<link rel="stylesheet" href="/public/css/admin-table.css">
<link rel="stylesheet" href="/public/css/admin-users.css"
<?php include $adminSidebarTemplate ?>
<body>
    <div class="content">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search by username, first name, or last name">
            <button id="sort-button" class="search-bar-button">Sort ID ↑</button>
            <button id="admin-filter-button" class="search-bar-button">Is Admin ✓</button>
            <button id="sub-filter-button" class="search-bar-button">Is Subscribed ✓</button>
            <button id="enable-filter-button" class="search-bar-button">Filter Disabled ✗</button>
            <button id="add-user-button" class="search-bar-button">New User</button>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Admin</th>
                    <th>Subscribed</th>
                    <th>Menu</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="pagination">
            <button id="prevPageButton">◄</button>
            <button id="currentPageButton">1</button>
            <button id="nextPageButton">►</button>
        </div>

        <div id="editUserModal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-edit">&times;</span>
                <h2>Edit User</h2>
                <table class="edit-user-modal">
                    <tr>
                        <td><label for="editUsername">Username</label></td>
                        <td><input type="text" id="editUsername" name="username" disabled="disabled" required></td>
                    </tr>
                    <tr>
                        <td><label for="editFirstName">First Name</label></td>
                        <td><input type="text" id="editFirstName" name="firstName" required></td>
                    </tr>
                    <tr>
                        <td><label for="editLastName">Last Name</label></td>
                        <td><input type="text" id="editLastName" name="lastName"></td>
                    </tr>
                    <tr>
                        <td><label for="editPassword">New Password</label></td>
                        <td><input type="password" id="editPassword" name="password"></td>
                    </tr>
                    <tr>
                        <td><label for="editStatusAdmin">Admin Status</label></td>
                        <td><select id="editStatusAdmin" name="statusAdmin">
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="editStatusSubscription">Subscription Status</label></td>
                        <td><select id="editStatusSubscription" name="statusSubscription">
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <button type="submit" class="submit-edit" id="saveEditButton">Save</button>
            </div>
        </div>
        <div id="newUserModal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-user">&times;</span>
                <h2>New User</h2>
                <table class="new-user-modal">
                    <tr>
                        <td><label for="newUsername">Username</label></td>
                        <td><input type="text" id="newUsername" name="username" required></td>
                    </tr>
                    <tr>
                        <td><label for="newFirstName">First Name</label></td>
                        <td><input type="text" id="newFirstName" name="firstName" required></td>
                    </tr>
                    <tr>
                        <td><label for="newLastName">Last Name</label></td>
                        <td><input type="text" id="newLastName" name="lastName"></td>
                    </tr>
                    <tr>
                        <td><label for="newPassword">Password</label></td>
                        <td><input type="password" id="newPassword" name="password"></td>
                    </tr>
                    <tr>
                        <td><label for="newPasswordConfirmation">Password Confirmation</label></td>
                        <td><input type="password" id="newPasswordConfirmation" name="passwordConfirmation"></td>
                    </tr>
                </table>
                <button type="submit" class="submit-new-user" id="newUserButton">Add User</button>
            </div>
        </div>
    </div>
    <script src="/public/js/admin-users.js"></script>
</body>
