<?php
$pageTitle = 'Movie Dashboard';
$stylesheet = '/public/css/admin-movies.css';
$script = 'admin.js';
$adminSidebarTemplate = BASE_PATH . "/public/templates/admin-sidebar.php";
$username = $_SESSION['username'];
include BASE_PATH . "/public/templates/header.php";

/**
 * @param $modalId : "new-content" | "edit-content"
 * @param $modalTitle : "New Content" | "Edit Content"
 */
function showUploadModal($modalId, $modalTitle) {
    $isRequired = ($modalId === "new-content") ? "required" : "";
    echo <<< HTML
    <div id="$modalId-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-$modalId-modal">&times;</span>
            <h2>$modalTitle</h2>
            <form id="$modalId-form" enctype="multipart/form-data">
                <table class="new-content-modal">
                    <tr>
                        <td><label for="movie-title">Title</label></td>
                        <td><input type="text" name="title" class="movie-title" id="movie-title" required/></td>
                    </tr>
                    <tr>
                        <td><label for="movie-description">Description</label></td>
                        <td>
                            <textarea
                                name="description"
                                class="movie-description"
                                cols="auto"
                                rows="5"
                                id="movie-description"
                            ></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="movie-release-date">Release Date</label></td>
                        <td>
                            <input
                                type="date"
                                name="release-date"
                                class="movie-release-date"
                                id="movie-release-date"
                                required
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="movie-thumbnail">Thumbnail</label></td>
                        <td><input type="file" name="thumbnail" class="movie-thumbnail" id="movie-thumbnail" $isRequired />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="movie-video">Video</label></td>
                        <td><input type="file" name="video" class="movie-video" id="movie-video" $isRequired /></td>
                    </tr>
                </table>
                <button type="submit" class="submit-$modalId-button">Save</button>
            </form>
        </div>
    </div>
    HTML;
}
?>

<link rel="stylesheet" href="/public/css/admin-page.css">
<link rel="stylesheet" href="/public/css/admin-table.css">
<link rel="stylesheet" href="/public/css/admin-movies.css">
<?php include $adminSidebarTemplate ?>
<body>
    <div class="content">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search by title or description">
            <button id="sort-button" class="search-bar-button">Sort Title ↑</button>
            <button id="enable-filter-button" class="search-bar-button" style="display: none">Filter Disabled ✗</button>
            <button id="add-content-button" class="search-bar-button">New Content</button>
        </div>
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Release Date</th>
                <th>Content File Name</th>
                <th>Thumbnail File Name</th>
                <th>Menu</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php 
            showUploadModal("new-content", "New Content");
            showUploadModal("edit-content", "Edit Content");
        ?>
        <div class="pagination">
            <button id="prevPageButton">◄</button>
            <button id="currentPageButton">1</button>
            <button id="nextPageButton">►</button>
        </div>
    </div>
    <script src="/public/js/admin-movies.js"></script>
</body>
