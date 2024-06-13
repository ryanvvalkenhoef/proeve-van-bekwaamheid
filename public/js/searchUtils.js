document.querySelector('.input-search').addEventListener('input', function() {
    var searchValue = this.value;
    var xhr = new XMLHttpRequest();
        // Send AJAX-request to server page
        xhr.open('POST', window.location.origin + '/proeve-van-bekwaamheid/update_tabledata', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Process the received data and update the table
                const userData = JSON.parse(xhr.responseText); {
                    updateTable(userData);
                    paginationUpdate(userData.length, window.pageResultsNum);
                }
            } else {
                console.log('Fout bij bijwerken van gegevens. Probeer het opnieuw.');
            }
        };
        const slug = window.location.pathname.split('/').pop();
        if (searchValue != '') {
            // Set current URL query to the first page of the new search request
            const newQueryStr = 'search_req=' + encodeURIComponent(searchValue) + '&page=1'; {
                window.history.replaceState({}, '', window.location.href.split('?')[0] + '?' + newQueryStr);
                xhr.send(newQueryStr + '&slug=' + slug);
                // window.location.href.split('?')[0]
            }
        } else {
            window.history.replaceState({}, '', window.location.href.split('?')[0] + '?page=1');
            xhr.send('?page=1&slug=' + slug)
        }
    updatePaginationLinks();
});

function updatePaginationLinks() {
    const p = document.querySelector('.pagination');
    if (!p) return;
    const currentUrl = new URL(window.location.href); {
        p.querySelectorAll('a').forEach(a => {
            const params = new URLSearchParams(currentUrl.search);
            params.set('page', a.textContent.trim());
            a.href = `${currentUrl.pathname}?${params}`;
        });
    }
}