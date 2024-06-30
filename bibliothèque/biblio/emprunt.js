document.addEventListener("DOMContentLoaded", async () => {
  const userId = localStorage.getItem("userId");
  const empruntsContainer = document.getElementById("emprunts-container");

  try {
    const response = await fetch(`get_emprunts.php?id=${userId}`);
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const emprunts = await response.json();

    if (emprunts.length === 0) {
      empruntsContainer.innerHTML = "<p>Aucun emprunt trouvé.</p>";
      return;
    }

    empruntsContainer.innerHTML = emprunts
      .map(
        (emprunt) => `
        <div class="emprunt">
          <strong>Titre:</strong> ${emprunt.TITRE}<br>
          <strong>Auteur:</strong> ${emprunt.AUTEUR}<br>
          <strong>Année de Publication:</strong> ${
            emprunt.ANNEE_DE_PUBLICATION
          }<br>
          <strong>Genre:</strong> ${emprunt.GENRE}<br>
          <strong>Éditeur:</strong> ${emprunt.EDITEUR}<br>
          <strong>Langue:</strong> ${emprunt.LANGUE}<br>
          <strong>Date d'emprunt:</strong> ${new Date(
            emprunt.DATE_EMPRUNT
          ).toLocaleDateString()}<br>
          <strong>Date de retour:</strong> ${new Date(
            emprunt.DATE_RETOUR
          ).toLocaleDateString()}<br>
          <strong>Status:</strong> ${emprunt.STATUS}<br>
        </div>
        <hr>
      `
      )
      .join("");
    showToast("Livres empruntés chargés:", "success");
  } catch (error) {
    showToast("Erreur lors de la récupération des emprunts:", "erreur");
    console.error("Erreur lors de la récupération des emprunts:", error);
    // empruntsContainer.innerHTML =
    //   "<p>Erreur lors de la récupération des emprunts.</p>";
  }
});

function showToast(message, type) {
  const toastContainer = document.getElementById("toastContainer");

  const toast = document.createElement("div");
  toast.classList.add("toast");
  if (type === "success") {
    toast.style.backgroundColor = "#28a745";
  } else {
    toast.style.backgroundColor = "#dc3545";
  }
  toast.textContent = message;

  toastContainer.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("show");
  }, 100);

  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => {
      toast.remove();
    }, 500);
  }, 3000);
}
