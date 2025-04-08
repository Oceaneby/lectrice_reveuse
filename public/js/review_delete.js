document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete-review");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const reviewId = this.getAttribute("data-review-id");
      const csrfToken = this.getAttribute("data-csrf-token");
      if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
        fetch(`/review/${reviewId}/delete`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken,
          },
        })
          .then((response) => {
            // Vérifie si la réponse est correcte (HTTP 200)
            if (!response.ok) {
              throw new Error("Réponse du serveur non valide");
            }
            return response.json(); // Convertir la réponse en JSON
          })
          .then((data) => {
            console.log("Parsed Data:", data); // Vérifie ce que contient 'data' ici

            // Vérifie si 'data' est défini et contient la propriété 'success'
            if (data && data.success) {
              const reviewElement = document.getElementById(
                "review-" + reviewId
              );

              // Vérifie si l'élément existe avant d'essayer de le supprimer
              if (reviewElement) {
                reviewElement.remove(); 
              } else {
                console.log("Élément à supprimer non trouvé");
              }
            } else {
              console.log("Erreur:", data ? data.message : "Aucune réponse");
              alert("Une erreur est survenue lors de la suppression.");
            }
          })
          .catch((error) => {
            console.error("Erreur AJAX:", error);
            alert("Une erreur est survenue.");
          });
      }
    });
  });
});
