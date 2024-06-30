document.addEventListener("DOMContentLoaded", async () => {
  const params = new URLSearchParams(window.location.search);
  // const isbn = params.get("isbn");
  const isbn = parseInt(params.get("isbn"), 10);
  const bookDetails = document.getElementById("book-details");

  if (!isbn) {
    bookDetails.innerHTML = "<p>ISBN non fourni.</p>";
    return;
  }

  try {
    const response = await fetch(`specific.php?isbn=${isbn}`);
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const book = await response.json();
    if (book.error) {
      bookDetails.innerHTML = `<p>${book.error}</p>`;
      return;
    }
    // showToast(book, "success");

    // console.log(book);

    bookDetails.innerHTML = `
      <div class="book-details"><strong>ISBN:</strong> ${book.ISBN}</div>
      <div class="book-details"><strong>Titre:</strong> ${book.TITRE}</div>
      <div class="book-details"><strong>Auteur:</strong> ${book.AUTEUR}</div>
      <div class="book-details"><strong>Année de Publication:</strong> ${book.ANNEE_DE_PUBLICATION}</div>
      <div class="book-details"><strong>Genre:</strong> ${book.GENRE}</div>
      <div class="book-details"><strong>Éditeur:</strong> ${book.EDITEUR}</div>
      <div class="book-details"><strong>Langue:</strong> ${book.LANGUE}</div>
      <div class="book-details"><strong>Quantité:</strong> ${book.QUANTITE}</div>
      <div class="book-details"><strong>Statut:</strong> ${book.STAT}</div>
    `;
    // if (book.STAT === "disponible") {
    if (book.QUANTITE > 0) {
      bookDetails.innerHTML += `<button id="borrow-button">Emprunter</button>`;
      document.getElementById("borrow-button").addEventListener("click", () => {
        window.location.href = `emprunter.html?isbn=${isbn}`;
      });
    }
    showToast("Karmel est ici", "success");
  } catch (error) {
    console.error(
      "Erreur lors de la récupération des détails du livre:",
      error
    );
    showToast("Erreur lors de la récupération des détails du livre", "erreur");
    bookDetails.innerHTML =
      "<p>Erreur lors de la récupération des détails du livre.</p>";
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
