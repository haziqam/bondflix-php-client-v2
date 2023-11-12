<?php
$pageTitle = 'Media Management';
$stylesheet = '/public/css/admin-genres.css';
$script = 'admin-genres.js';
include BASE_PATH . "/public/templates/header.php";
$adminSidebarTemplate = BASE_PATH . "/public/templates/admin-sidebar.php";
$username = $_SESSION['username'];
?>

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Mono&display=swap"
  rel="stylesheet"
/>
<link rel="stylesheet" href="/public/css/admin-page.css">
<link rel="stylesheet" href="/public/css/admin-media-management.css">
<script src="/public/js/admin-media-management.js" defer></script>


<body>
<?php include $adminSidebarTemplate ?>
<div class="content">
    <div class="container">
        <div class="genre-container">
            <h2>Manage Genre</h2>
            <div class="input-with-button">
                <div>         
                    <label for="edit_genre_id">Select a Genre to Edit</label>
                    <select name="edit_genre_id" id="edit_genre_id" required></select>
                </div>
                <div>
                    <button id="edit_genre_button" type="submit">Edit Genre</button>
                    <button id="delete_genre_button" type="submit">Delete Genre</button>
                </div>
            </div>
            <div class="input-with-button">
                <form id="add-genre-form">
                    <div>
                        <label for="input-genre">Add New Genre</label>
                        <input type="text" name="genre_name" id="input-genre" placeholder="New Genre Name" required>
                    </div>
                    <div>
                        <button type="submit" id="add-genre-button">Add Genre</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="content-genre-container">
            <div>
                <h2>Manage content genre</h2>
                <div class="input-with-button">
                    <form id="find-content-genre">
                        <div>
                            <label for="content-id">Find a Content's Genres</label>
                            <input type="text" id="content-id" required placeholder="Content Id" />
                        </div>
                        <div>
                            <button type="submit" id="find-content-genre">Find Genre</button>
                        </div>
                    </form>
                </div>
                <div class="input-with-button">
                    <form id="add-new-content-genre">
                        <div>
                            <label for="content-new-genre-dropdown">Add a Content's Genre</label>
                            <select name="content-new-genre-dropdown" id="content-new-genre-dropdown" required>
                            </select>
                        </div>
                        <div>
                            <button type="submit" id="add-content-genre">Add Genre</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <table class="admin-table" id="content-genre-table">
                    <tr>
                        <th>Genre ID</th>
                        <th>Genre Name</th>
                        <th>Delete</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="editGenreModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Genre</h2>
            <table class="edit-genre-modal">
                <tr>
                    <td><label for="editGenreName">New genre name</label></td>
                    <td><input type="text" id="editGenreName" name="genreName" required></td>
                </tr>
            </table>
            <button type="submit" class="submit-edit" id="saveEditGenreButton">Save</button>
        </div>
    </div>
</div>
</body>
