function updateContents(contents) {
    const premiumContentContainer = document.getElementById('premium-content-container');
    premiumContentContainer.innerHTML = '';

    if (contents && contents.length > 0) {
        contents.forEach(content => {
            const contentDiv = document.createElement('div');
            contentDiv.classList.add('content-item', 'card');

            const image = document.createElement('img');
            image.src = `http://rest_server:3000/static/thumbnails?id=${content.id}`;
            image.classList.add('content-image');

            const title = document.createElement('h3');
            title.textContent = content.title;

            const description = document.createElement('p');
            description.textContent = content.description;

            const referralLink = document.createElement('a');
            referralLink.href = `http://bondflix-spa/watch?id=${content.id}`;
            referralLink.textContent = 'View More';
            referralLink.classList.add('referral-link');

            contentDiv.appendChild(image);
            contentDiv.appendChild(title);
            contentDiv.appendChild(description);
            contentDiv.appendChild(referralLink);

            premiumContentContainer.appendChild(contentDiv);
        });
    } else {
        const noResultsMessage = document.createElement("h2");
        noResultsMessage.style.fontWeight = "normal";
        noResultsMessage.textContent = "No premium content available at the moment";
        premiumContentContainer.appendChild(noResultsMessage);
    }
}



function clearData() {
    const dataContainer = document.getElementById("premium-content-container");
    if (dataContainer && dataContainer.firstChild !== null){
        while (dataContainer.firstChild) {
            dataContainer.removeChild(dataContainer.firstChild);
        }
    }
}
async function fetchData() {
    try {
        const httpClient = new HttpClient();
        let url = `http://localhost:3000/api/v1/contents`;
        const response = await httpClient.get(url, null, false);

        const data = JSON.parse(response.body).data
        clearData();
        updateContents(data);
    } catch (error) {
        console.log(error);
        console.error("An error occurred during fetch data:", error);
        alert("An error occurred during fetch data.");
    }
}

fetchData().then(() => {})