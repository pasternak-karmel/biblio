document.addEventListener("DOMContentLoaded", async () => {
  let books = await fetchLivres();

  if (!Array.isArray(books)) {
    console.error("Books data is not an array");
    books = [];
  }

  const bookList = document.getElementById("bookList");
  const searchInput = document.getElementById("searchInput");
  const filters = document.querySelectorAll(".filter");

  function displayBooks(booksToDisplay) {
    bookList.innerHTML = "";
    booksToDisplay.forEach((book) => {
      const li = document.createElement("li");
      li.innerHTML = `
                <div class="book-details"><strong>Titre:</strong> ${book.TITRE}</div>
                <div class="book-details"><strong>Auteur:</strong> ${book.AUTEUR}</div>
                <div class="book-details"><strong>Année de Publication:</strong> ${book.ANNEE_DE_PUBLICATION}</div>
                <div class="book-details"><strong>Genre:</strong> ${book.GENRE}</div>
                <div class="book-details"><strong>Éditeur:</strong> ${book.EDITEUR}</div>
                <div class="book-details"><strong>Langue:</strong> ${book.LANGUE}</div>
                <button class="view-details" data-isbn="${book.ISBN}">Afficher</button>
                `;

      // li.innerHTML = `
      //   <div class="book-details"><strong>Titre:</strong> ${book.titre}</div>
      //   <div class="book-details"><strong>Auteur:</strong> ${book.auteur}</div>
      //   <div class="book-details"><strong>Année de Publication:</strong> ${book.annee_de_publication}</div>
      //   <div class="book-details"><strong>Genre:</strong> ${book.genre}</div>
      //   <div class="book-details"><strong>Éditeur:</strong> ${book.editeur}</div>
      //   <div class="book-details"><strong>Langue:</strong> ${book.langue}</div>
      // `;
      bookList.appendChild(li);
      // <div class="book-status ${statusClass}">${book.status}</div>
      // <div class="book-details"><strong>Quantité:</strong> ${book.quantity}</div>
    });
    document.querySelectorAll(".view-details").forEach((button) => {
      button.addEventListener("click", (event) => {
        const isbn = event.target.dataset.isbn;
        window.location.href = `specific.html?isbn=${isbn}`;
      });
    });
  }

  function getStatusClass(status) {
    if (status === "disponible") return "status-disponible";
    if (status === "emprunté") return "status-emprunte";
    if (status === "en retard") return "status-en-retard";
    return "";
  }

  function filterBooks() {
    let filteredBooks = books;

    const searchTerm = searchInput.value.toLowerCase();
    if (searchTerm) {
      filteredBooks = filteredBooks.filter(
        (book) =>
          book.TITRE.toLowerCase().includes(searchTerm) ||
          book.AUTEUR.toLowerCase().includes(searchTerm) ||
          book.EDITEUR.toLowerCase().includes(searchTerm)
      );
    }

    filters.forEach((filter) => {
      if (!filter.checked) {
        return;
      }
      const filterType = filter.dataset.filter;
      const filterValue = filter.value.toLowerCase();
      filteredBooks = filteredBooks.filter((book) =>
        book[filterType].toString().toLowerCase().includes(filterValue)
      );
    });

    displayBooks(filteredBooks);
  }

  searchInput.addEventListener("input", filterBooks);
  filters.forEach((filter) => filter.addEventListener("change", filterBooks));

  document
    .getElementById("borrowedBooksButton")
    .addEventListener("click", () => {
      const borrowedBooks = books.filter((book) => book.status === "emprunté");
      displayBooks(borrowedBooks);
    });

  displayBooks(books);
});

async function fetchLivres() {
  try {
    const response = await fetch("get_livre.php");
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const data = await response.json();

    showToast("Livres chargé avec success", "success");
    return data;
  } catch (error) {
    console.error("Erreur lors de la récupération des auteurs:", error);
    showToast(error, "error");
    return [];
  }
}

function showToast(message, type) {
  const toastContainer = document.getElementById("toastContainer");

  const toast = document.createElement("div");
  toast.classList.add("toast");
  toast.style.backgroundColor = type === "success" ? "#28a745" : "#dc3545";
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

// auteurs.forEach((auteur) => {
//   const option = document.createElement("option");
//   option.value = auteur.ID_AUTEUR;
// const books = [
//   {
//     title: "Le Nom du Vent",
//     author: "Patrick Rothfuss",
//     year: 2007,
//     genre: "Fantasy",
//     quantity: 5,
//     publisher: "DAW Books",
//     language: "Français",
//     status: "disponible",
//   },
//   {
//     title: "Harry Potter à l'école des sorciers",
//     author: "J.K. Rowling",
//     year: 1997,
//     genre: "Fantasy",
//     quantity: 3,
//     publisher: "Bloomsbury",
//     language: "Français",
//     status: "emprunté",
//   },
//   {
//     title: "L'Ordre du Phénix",
//     author: "J.K. Rowling",
//     year: 2003,
//     genre: "Fantasy",
//     quantity: 2,
//     publisher: "Bloomsbury",
//     language: "Anglais",
//     status: "en retard",
//   },
//   // Ajoutez plus de livres ici...
// ];
//   option.textContent = `${auteur.PRENOM} ${auteur.NOM} (${auteur.NATIONALITE})`;
//   auteurSelect.appendChild(option);
// });
