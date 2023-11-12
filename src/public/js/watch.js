let inMyList = false;
let contentGenres = {};
let contentId ;

const VideoElements = {
    videoSource: document.getElementById('video-source'),
    videoElement: document.getElementById('video-element'),
    title: document.getElementById('movie-title'),
    description: document.getElementById('movie-description'),
    releaseDate: document.getElementById('movie-release-date'),
    addButton: document.getElementById('add-button'),
    deleteButton: document.getElementById('delete-button'),
    genreContainer: document.getElementById('genre-container'),
}

async function fetchVideoData() {
    try {
        const httpClient = new HttpClient();
        contentId = helper.getUrlParameter('id');
        let url = `/api/content?content_id=${contentId}`;

        const response = await httpClient.get(url, null, false);
        const data = JSON.parse(response.body);

        if (data.success === true) {
            const contentFilePath = data.data[0].content_file_path;
            const movieTitle = data.data[0].title;
            const movieDescription = data.data[0].description;
            const movieReleaseDate = data.data[0].release_date;
            inMyList = await checkIfInMyList(contentId);
            updateTitleAndDescription(movieTitle, movieDescription, movieReleaseDate);
            updateSource(contentFilePath);
            showAddOrDeleteButton();
        } else {
            window.location.href = '/404';
        }
    } catch (error) {
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}

function updateSource(videoPath) {
    const videoElement = VideoElements.videoElement;
    if (videoElement) {
        videoElement.pause();
        VideoElements.videoSource.src = videoPath;
        videoElement.load();
        videoElement.play();
    }
}

function updateTitleAndDescription(title, description, releaseDate) {
    const titleElement = VideoElements.title;
    const descriptionElement = VideoElements.description;
    const releaseDateElement = VideoElements.releaseDate;

    if (titleElement && descriptionElement && releaseDateElement) {
        titleElement.innerHTML = title;
        descriptionElement.textContent = description;
        releaseDateElement.textContent = "Released at " + releaseDate;
    }
}

function updateGenres() {
    const genreContainer = VideoElements.genreContainer;
    if (genreContainer) {
        genreContainer.innerHTML = '';

        for (const genreId in contentGenres) {
            const genreName = contentGenres[genreId];
            const genreElement = document.createElement('p');
            genreElement.classList.add('genre');
            genreElement.innerText = genreName;
            if (genreContainer.children.length > 0) {
                genreElement.style.marginLeft = '5px';
            }
            genreContainer.appendChild(genreElement);
        }
    }
}

async function checkIfInMyList(contentId) {
    try {
        const httpClient = new HttpClient();
        const url = `/api/mylist/check?content_id=${contentId}&user_id=${userId}`;
        const response = await httpClient.get(url, null, false);
        const data = JSON.parse(response.body);
        return data.data === true;
    } catch (error) {
        console.error("An error occurred while checking if in my list:", error);
        return false;
    }
}

async function checkGenre(contentId) {
    try {
        const httpClient = new HttpClient();
        const url = `/api/content/genre?content_id=${contentId}`;
        const response = await httpClient.get(url, null, false);
        const data = JSON.parse(response.body);
        if (data.success){
            data.data.forEach((genre) => {
                contentGenres[genre.genre_id] = genre.genre_name;
            });
            updateGenres();
        }
    } catch (error) {
        console.error("An error occurred while checking if in my list:", error);
        return false;
    }
}
function showAddOrDeleteButton() {
    if (inMyList) {
        VideoElements.addButton.style.display = 'none';
        VideoElements.deleteButton.style.backgroundColor = 'red';
        VideoElements.deleteButton.style.display = 'block';
        VideoElements.deleteButton.innerText = 'Delete from My List';

    } else {
        VideoElements.addButton.style.backgroundColor = 'yellow';
        VideoElements.addButton.style.color = 'black';
        VideoElements.addButton.style.display = 'block';
        VideoElements.addButton.innerText = 'Add to My List';
        VideoElements.deleteButton.style.display = 'none';
    }
}

/**
 * Register the events;
 */
VideoElements.addButton.addEventListener('click', async () => {
    try {
        const httpClient = new HttpClient();
        const url = `/api/mylist?content_id=${contentId}&user_id=${userId}`;
        const response = await httpClient.post(url, null, false);
        const data = JSON.parse(response.body);
        if (data.success === true) {
            inMyList = true;
            showAddOrDeleteButton();
            alert('Added to My List successfully.');
        }
    } catch (error) {
        console.error("An error occurred while adding to my list:", error);
    }
});

VideoElements.deleteButton.addEventListener('click', async () => {
    const confirmDelete = confirm('Are you sure you want to delete from My List?');
    if (confirmDelete) {
        try {
            const httpClient = new HttpClient();
            const url = `/api/mylist?content_id=${contentId}&user_id=${userId}`;
            const response = await httpClient.delete(url, null, false);
            const data = JSON.parse(response.body);
            if (data.success === true) {
                inMyList = false;
                showAddOrDeleteButton();
                alert('Deleted from My List successfully.');
            }
        } catch (error) {
            console.error("An error occurred while deleting from my list:", error);
            alert('Failed to delete from My List.');
        }
    }
});


fetchVideoData();
checkGenre(contentId);