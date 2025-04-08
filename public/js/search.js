function searchBooks(event) {
    const query = document.getElementById('search-input').value; // Récupère le texte du champ de recherche
    const searchResults = document.getElementById('search-results');

    if (query.length > 5) { // Effectuer la requête AJAX
        
        fetch(searchUrl +'?query=' + query, {
            method: 'GET',
            headers: {
                'Accept': 'application/json', // Indiquer que la réponse attendue est en JSON
            },
        })
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data);
            // Vider les anciens résultats
            searchResults.innerHTML = '';

            // Si des résultats sont trouvés
            if (data.length > 0) {
                data.forEach(book => {
                    const bookElement = document.createElement('div');
                    bookElement.classList.add('p-4', 'bg-gray-800', 'text-white', 'mb-2');
                    bookElement.innerHTML = `<strong>${book.title}</strong> `;
                    bookElement.onclick = function () {
                        window.location.href = `/book/${book.id}`; // Rediriger vers la page du livre
                    };
                    searchResults.appendChild(bookElement);
                });

                // Afficher la liste des résultats
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la recherche :', error);
        });
    } else {
        searchResults.classList.add('hidden'); // Cacher les résultats si la recherche est trop courte
    }
    document.getElementById('search-button').addEventListener('click', function(event) {
        event.preventDefault();  // Empêche la soumission du formulaire
        searchBooks(event);      // Appelle la fonction searchBooks
    });
}