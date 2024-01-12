document.getElementById('searchInput').addEventListener('keyup', async function () {
    const searchText = this.value;
    const endpoint = 'View/User/search_wikis.php?search=' + encodeURIComponent(searchText);

    try {
        const response = await fetch(endpoint);
        const data = await response.json();
        updateSearchResults(data);
    } catch (error) {
        console.error('Error:', error);
    }
});

document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('View/User/search_wikis.php');
        const data = await response.json();
        updateSearchResults(data);
    } catch (error) {
        console.error('Error:', error);
    }
});

function updateSearchResults(wikis) {
    if (!wikis.length) {
        document.getElementById('resultsArea').innerHTML = 'No wikis found.';
        return;
    }

    let resultsHtml = '';
    wikis.forEach(wiki => {
        resultsHtml += `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">${wiki.title}</h5>
                    <p class="card-text">${wiki.content}</p>
                    <a href="${wiki.link}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        `;
    });

    document.getElementById('resultsArea').innerHTML = resultsHtml;
}