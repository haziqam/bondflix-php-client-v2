const helper = new Helper();
const DashboardTable = {
    data: {},
    firstContent: null,
    currentPage: 1,
    totalPages: 1,
    isAscending: true,
    filterEnabled: false,
    pageSize: 20,
    searchState: false,
};
let debounceTimer;

const Elements = {
    recommendationsContainer: document.getElementById("search-result-container"),
    navbarSearchInput: document.getElementById("navbar-search-input"),
    mostRecommend: document.getElementById("most-recommended"),
    mostRecommendWrapper: document.getElementById("most-recommended-wrapper"),
    prevPageButton: document.getElementById("prevPageButton"),
    nextPageButton: document.getElementById("nextPageButton"),
    currentPageButton: document.getElementById("currentPageButton"),
    playMainLinkButton: document.getElementById("play-btn-link"),
    genreDropdown: document.getElementById("genre-dropdown"),
    dateFilterSelect: document.getElementById("release-date-filter"),
    filterEnableButton: document.getElementById("navbar-filter-button"),
    sortFilterButton: document.getElementById("sort-filter-button")
};

function handlePaginationButtons() {
    Elements.currentPageButton.innerHTML = DashboardTable.currentPage;
    Elements.prevPageButton.disabled = DashboardTable.currentPage === 1;
    Elements.nextPageButton.disabled =
        DashboardTable.currentPage === DashboardTable.totalPages ||
        DashboardTable.totalPages === 0;
}
function updateContents(contents) {
    const recommendationsContainer = Elements.recommendationsContainer;
    let firstContent;
    if (contents && contents.length > 0) {
        if (!DashboardTable.firstContent) {
            DashboardTable.firstContent = contents[0];
        }

        if (DashboardTable.firstContent !== contents[0]){
            firstContent = contents[0];
        } else {
            firstContent = DashboardTable.firstContent;
        }
        const contentId = firstContent.content_id;
        const thumbnailPath = firstContent.thumbnail_file_path;
        const movieTitle = firstContent.title;
        const movieDescription = firstContent.description;

        if (Elements.mostRecommend) {
            Elements.mostRecommend.style.backgroundImage = `url('${thumbnailPath}')`;
        }

        if (document.querySelector(".description-card")) {
            const descriptionCard = document.querySelector(".description-card");
            descriptionCard.querySelector("h2").textContent = movieTitle;
            descriptionCard.querySelector("p").textContent = movieDescription;
        }

        if (Elements.playMainLinkButton) {
            Elements.playMainLinkButton.href = `/watch?id=${contentId}`;
        }
        addRecommendation(
            contentId,
            thumbnailPath,
            movieTitle,
            movieDescription
        );

        for (let i = 1; i < contents.length; i++) {
            const content = contents[i];
            const contentId = content.content_id;
            const thumbnailPath = content.thumbnail_file_path;
            const movieTitle = content.title;
            const movieDescription = content.description;
            addRecommendation(
                contentId,
                thumbnailPath,
                movieTitle,
                movieDescription
            );
        }

        if (DashboardTable.searchState) {
            Elements.mostRecommendWrapper.style.maxHeight = "0";
        } else {
            Elements.mostRecommendWrapper.style.maxHeight = "100vh";
        }
    } else {
        const noResultsMessage = document.createElement("h2");
        noResultsMessage.style.fontWeight = "normal";
        noResultsMessage.textContent = "No movies at the moment";
        recommendationsContainer.appendChild(noResultsMessage);
    }
}

function clearRecommendations() {
    const recommendationsContainer = Elements.recommendationsContainer;
    if (recommendationsContainer && recommendationsContainer.firstChild !== null){
        while (recommendationsContainer.firstChild) {
            recommendationsContainer.removeChild(recommendationsContainer.firstChild);
        }
    }
}
async function fetchData() {
    try {
        const httpClient = new HttpClient();
        const query = Elements.navbarSearchInput.value.trim();
        let url = `/api/content?query=${query}&sortAscending=${DashboardTable.isAscending}&page=${DashboardTable.currentPage}&pageSize=${DashboardTable.pageSize}`;

        if (DashboardTable.filterEnabled) {
            if (Elements.genreDropdown){
                const genreValue = Elements.genreDropdown.value;
                url += `&genre_id=${genreValue}`;
            }
            if (Elements.dateFilterSelect){
                const dateValue = Elements.dateFilterSelect.value;
                url += `&released_before=${dateValue}`
            }
        }
        const response = await httpClient.get(url, null, false);

        const data = JSON.parse(response.body).data;
        clearRecommendations();
        DashboardTable.totalPages = parseInt(response.headers["x-total-pages"]);
        updateContents(data);
        handlePaginationButtons();
    } catch (error) {
        console.log(error);
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}

function initEventListeners() {
    Elements.navbarSearchInput.addEventListener("input", () => {
        const query = Elements.navbarSearchInput.value.trim();
        DashboardTable.searchState = query !== "";
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchData();
        }, 500);
    });

    Elements.prevPageButton.addEventListener("click", () => {
        if (DashboardTable.currentPage > 1) {
            DashboardTable.currentPage--;
            fetchData();
        }
    });

    Elements.nextPageButton.addEventListener("click", () => {
        if (DashboardTable.currentPage < DashboardTable.totalPages) {
            DashboardTable.currentPage++;
            fetchData();
        }
    });

    if (Elements.genreDropdown) {
        Elements.genreDropdown.addEventListener("change", () => {
            if (DashboardTable.filterEnabled) {
                DashboardTable.searchState = true;
                fetchData();
            }
        });
    }

    if (Elements.dateFilterSelect) {
        Elements.dateFilterSelect.addEventListener("change", () => {
            if (DashboardTable.filterEnabled) {
                DashboardTable.searchState = true;
                fetchData();
            }
        });
    }

    Elements.sortFilterButton.addEventListener("click", () => {
        DashboardTable.isAscending = !DashboardTable.isAscending;
        Elements.sortFilterButton.textContent = `Sort Title ${
            DashboardTable.isAscending ? "↑" : "↓"
        }`;
        if (DashboardTable.filterEnabled) {
            DashboardTable.searchState = true;
            fetchData();
        }
    });

    Elements.filterEnableButton.addEventListener("click", () => {
        DashboardTable.filterEnabled = !DashboardTable.filterEnabled;
        DashboardTable.searchState = false;
        Elements.filterEnableButton.textContent = `Filter ${
            DashboardTable.filterEnabled ? "✓" : "✗"
        }`;
        Elements.filterEnableButton.style.color =
            DashboardTable.filterEnabled ? "white" : "black";
        Elements.filterEnableButton.style.backgroundColor =
            DashboardTable.filterEnabled ? "black" : "white";
        fetchData();
    });
}

function addRecommendation(contentId, thumbnailPath, title) {
    const link = document.createElement("a");
    link.href = `/watch?id=${contentId}`;
    link.classList.add("recommendation-link");

    const recommendation = document.createElement("div");
    recommendation.classList.add("recommendation");

    const image = document.createElement("img");
    image.src = thumbnailPath;
    image.alt = "Movie Thumbnail";

    const titleElement = document.createElement("h2");
    titleElement.classList.add("recommendation-title");
    titleElement.textContent = title;

    recommendation.appendChild(image);
    recommendation.appendChild(titleElement);
    link.appendChild(recommendation);
    if (Elements.recommendationsContainer) {
        Elements.recommendationsContainer.appendChild(link);
    }
}

initEventListeners();
fetchData();
