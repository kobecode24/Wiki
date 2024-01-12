document.getElementById('searchInput').addEventListener('keyup', function() {
    var searchText = this.value;

    var endpoint = searchText ? 'View/User/search_wikis.php?search=' + encodeURIComponent(searchText) : 'View/User/search_wikis.php?search=all';

    fetch(endpoint)
        .then(response => response.json())
        .then(data => {
            updateSearchResults(data);
        })
        .catch(error => console.error('Error:', error));
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

document.addEventListener('DOMContentLoaded', function() {
    fetch('View/User/search_wikis.php?search=all')
        .then(response => response.json())
        .then(data => {
            updateSearchResults(data);
        })
        .catch(error => console.error('Error:', error));
});
