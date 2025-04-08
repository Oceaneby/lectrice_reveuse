console.log("🔥 Script review_edit.js chargé");

function renderRatingCircles(note) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        const colorClass = i <= note ? 'bg-yellow-500' : 'bg-gray-300';
        html += `<span class="inline-block w-5 h-5 review-rating ${colorClass} rounded-full"></span>`;
    }
    return html;
}

function bindReviewFormHandler() {
    const form = document.getElementById('edit-review-form');
    console.log("Formulaire d'édition trouvé :", form);
    if (!form) {
        console.log("🚫 Formulaire d'édition non trouvé.");
        return;
    }

    // Supprime l'ancien listener s'il y en a un
    const oldForm = form.cloneNode(true);
    form.replaceWith(oldForm);
    const freshForm = document.getElementById('edit-review-form');

    console.log('✅ Formulaire bindé ou rebondé.');

    freshForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        console.log('📤 Formulaire soumis via AJAX');

        const formData = new FormData(freshForm);
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        try {
            const response = await fetch(freshForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Erreur réseau');
            }

            const data = await response.json();
            console.log('✅ Données reçues :', data);

            if (data.success) {
                // 🔄 Met à jour le commentaire affiché
                const reviewElement = document.getElementById('review-' + data.reviewId);
                if (reviewElement) {
                    reviewElement.querySelector('.review-text').textContent = data.reviewText;
                    reviewElement.querySelector('.review-date').textContent = data.reviewDate;

                    const ratingContainer = reviewElement.querySelector('p.text-yellow-500');
                    if (ratingContainer) {
                        ratingContainer.innerHTML = renderRatingCircles(data.rating);
                    }
                }

                // 📝 Met à jour les champs du formulaire
                const reviewTextarea = freshForm.querySelector('textarea[name="review[review_text]"]');
                if (reviewTextarea) {
                    reviewTextarea.value = data.reviewText;
                    reviewTextarea.textContent = data.reviewText;
                }

                const ratingInputs = freshForm.querySelectorAll(`input[name="review[rating]"]`);
                ratingInputs.forEach(input => {
                    input.checked = (input.value === data.rating.toString());
                });

                console.log("✅ Mise à jour du formulaire et de l'affichage terminée.");

                // 🔁 Rebind le formulaire pour permettre une autre modification
                bindReviewFormHandler();
               
            } else {
                alert('❌ Erreur lors de la mise à jour du commentaire.');
            }

        } catch (error) {
            console.error('❌ Erreur AJAX :', error);
            alert('Une erreur est survenue.');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(bindReviewFormHandler, 100);
});


