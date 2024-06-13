function paginationUpdate(userCount, numResultsPerPage) {
    const totalPages = Math.ceil(userCount / numResultsPerPage);
    if (totalPages > 0) {
        const paginationElement = document.querySelector('.pagination');
        paginationElement.innerHTML = ''; // Clear existing pagination

        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = parseInt(urlParams.get('page')) || 1;

        if (currentPage > 1) {
            const prevPage = currentPage - 1;
            const prevLink = createPaginationLink(prevPage, 'Vorig');
            paginationElement.appendChild(prevLink);
        }

        if (currentPage > 3) {
            const startLink = createPaginationLink(1, '1');
            paginationElement.appendChild(startLink);
            paginationElement.appendChild(document.createElement('li')).innerText = '...';
        }

        for (let i = Math.max(1, currentPage - 2); i < Math.min(currentPage + 3, totalPages + 1); i++) {
            const pageLink = createPaginationLink(i, i.toString());
            paginationElement.appendChild(pageLink);
        }

        if (currentPage < totalPages - 2) {
            paginationElement.appendChild(document.createElement('li')).innerText = '...';
            const endLink = createPaginationLink(totalPages, totalPages.toString());
            paginationElement.appendChild(endLink);
        }

        if (currentPage < totalPages) {
            const nextPage = currentPage + 1;
            const nextLink = createPaginationLink(nextPage, 'Volgende');
            paginationElement.appendChild(nextLink);
        }
    }
}

function createPaginationLink(pageNumber, text) {
    const link = document.createElement('li');
    const linkAnchor = document.createElement('a');
    linkAnchor.href = updateQueryStringParameter(window.location.href, 'page', pageNumber);
    linkAnchor.innerText = text;
    link.appendChild(linkAnchor);
    return link;
}

function updateQueryStringParameter(uri, key, value) {
    const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    const separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}