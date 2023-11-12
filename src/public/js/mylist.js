const MyListTable = {
    data: {},
    currentPage: 1,
    totalPages: 1,
    isAscending: true,
    filterEnabled: false,
    pageSize: 20,
    searchState: false,
};

const MyListElements = {
    myListMovies: document.getElementById('search-result-mylist-container'),
}

function initEventListeners()
{
    Elements.navbarSearchInput.addEventListener("input", () => {
        const query = Elements.navbarSearchInput.value.trim();
        MyListTable.searchState = query !== '';
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchMyListData();
        }, 500);
    });

    Elements.prevPageButton.addEventListener("click", () => {
        if (MyListTable.currentPage > 1) {
            MyListTable.currentPage--;
            fetchMyListData();
        }
    });

    Elements.nextPageButton.addEventListener("click", () => {
        if (MyListTable.currentPage < MyListTable.totalPages) {
            MyListTable.currentPage++;
            fetchMyListData();
        }
    });
}

async function fetchMyListData()
{
    try {
        const httpClient = new HttpClient();
        const query = Elements.navbarSearchInput.value.trim();
        let url = `/api/mylist?query=${query}&user_id=${userId}&sortAscending=${MyListTable.isAscending}&page=${MyListTable.currentPage}&pageSize=${MyListTable.pageSize}`;

        if (MyListTable.filterEnabled) {
            url += ``;
        }
        const response = await httpClient.get(url, null, false);
        const data = JSON.parse(response.body).data;
        MyListTable.totalPages = parseInt(response.headers["x-total-pages"]);
        updateMyListContents(data);
        handlePaginationButtons();
    } catch (error) {

        console.log(error)
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}

function updateMyListContents(contents) {
    MyListElements.myListMovies.innerHTML = '';

    if (contents && contents.length > 0) {
        contents.forEach((content) => {
            const contentId = content.content_id;
            const thumbnailPath = content.thumbnail_file_path;
            const movieTitle = content.title;
            const movieDescription = content.description;
            addMyListMovie(contentId, thumbnailPath, movieTitle, movieDescription);
        })
    } else {
        const noResultsMessage = document.createElement('h2');
        noResultsMessage.style.fontWeight = "normal";
        noResultsMessage.textContent = 'No movies at the moment';
        MyListElements.myListMovies.appendChild(noResultsMessage);
    }
}

function addMyListMovie(contentId, thumbnailPath, title) {
    const link = document.createElement('a');
    link.href = `/watch?id=${contentId}`;
    link.classList.add('recommendation-link');

    const recommendation = document.createElement('div');
    recommendation.classList.add('recommendation');

    const image = document.createElement('img');
    image.src = thumbnailPath;
    image.alt = 'Movie Thumbnail';

    const titleElement = document.createElement('h2');
    titleElement.classList.add('recommendation-title');
    titleElement.textContent = title;

    recommendation.appendChild(image);
    recommendation.appendChild(titleElement);
    link.appendChild(recommendation);

    MyListElements.myListMovies.appendChild(link);
}

initEventListeners();
fetchMyListData();