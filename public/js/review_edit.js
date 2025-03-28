console.log("Le fichier review_edit.js est chargé et exécuté.");

function renderRatingCircles(note) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        const colorClass = i <= note ? 'bg-yellow-500' : 'bg-gray-300';
        html += `<span class="inline-block w-5 h-5 review-rating ${colorClass} rounded-full"></span>`;
    }
    return html;
}


document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const form = document.getElementById('edit-review-form');
        console.log('Formulaire trouvé:', form);

        if (!form) {
            console.log("Le formulaire n'a pas été trouvé.");
            return;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log('Formulaire soumis');

            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                console.log('Réponse du serveur:', response);

                if (!response.ok) {
                    throw new Error('Erreur de la requête');
                }

                const data = await response.json();
                console.log('Données reçues:', data);

                if (data.success) {

                    // 🔄 Met à jour l'affichage visible
                    const reviewElement = document.getElementById('review-' + data.reviewId);
                    if (reviewElement) {
                    console.log("donald trump est une merde", reviewElement.querySelector('.review-text'))

                        reviewElement.querySelector('.review-text').textContent = data.reviewText;
                        
                        const ratingContainer = reviewElement.querySelector('p.text-yellow-500');
                        if (ratingContainer) {
                        ratingContainer.innerHTML = renderRatingCircles(data.rating);
                         }


                        reviewElement.querySelector('.review-date').textContent = data.reviewDate;
                    }

                    // 📝 Met à jour le champ commentaire dans le formulaire
                    const reviewTextarea = form.querySelector('textarea[name="review[review_text]"]');
                    if (reviewTextarea) {
                        reviewTextarea.value = data.reviewText;
                    }

                    // ⭐ Met à jour la note sélectionnée
                    const ratingInput = form.querySelector(
                        `input[name="review[rating]"][value="${data.rating}"]`
                    );
                    if (ratingInput) {
                        ratingInput.checked = true;
                    }

                } else {
                    console.log('Élément du commentaire introuvable');
                    alert('Erreur lors de la mise à jour du commentaire.');
                }

            } catch (error) {
                console.error('Erreur AJAX:', error);
                alert('Une erreur est survenue.');
            }
        });

    }, 100);
});
