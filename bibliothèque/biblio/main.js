document.addEventListener("DOMContentLoaded", async () => {
  let books = await fetchLivres();

  if (!Array.isArray(books)) {
    console.error("Books data is not an array");
    books = [];
  }

  const bookDisplay = document.getElementById("book-display");
  const categoryFilter = document.getElementById("categories");
  const genreFilters = document.querySelectorAll('input[name="genre"]');

  function displayBooks(filteredBooks) {
    bookDisplay.innerHTML = "";
    filteredBooks.forEach((book) => {
      const bookCard = document.createElement("div");
      bookCard.className = "book-card";
      bookCard.innerHTML = `
                <img src="/image/${book.IMAGE}" alt="Couverture du livre">
                <h3>${book.TITRE}</h3>
                <p>Auteur: ${book.AUTEUR}</p>
                <p>Éditeur: ${book.EDITEUR}</p>
                <p>Genre: ${book.GENRE}</p>
                <p>Année de Publication: ${book.ANNEE_DE_PUBLICATION}</p>
                <p>Langue: ${book.LANGUE}</p>
                <button class="view-details" data-isbn="${book.ISBN}">Afficher</button>
            `;
      bookDisplay.appendChild(bookCard);
    });
    document.querySelectorAll(".view-details").forEach((button) => {
      button.addEventListener("click", (event) => {
        const isbn = event.target.dataset.isbn;
        window.location.href = `specific.html?isbn=${isbn}`;
      });
    });
  }

  function filterBooks() {
    const selectedCategory = categoryFilter.value;
    const selectedGenres = Array.from(genreFilters)
      .filter((checkbox) => checkbox.checked)
      .map((checkbox) => checkbox.value);

    const filteredBooks = books.filter((book) => {
      const categoryMatch =
        selectedCategory === "all" || book.category === selectedCategory;
      const genreMatch =
        selectedGenres.length === 0 || selectedGenres.includes(book.genre);
      return categoryMatch && genreMatch;
    });

    displayBooks(filteredBooks);
  }

  categoryFilter.addEventListener("change", filterBooks);
  genreFilters.forEach((checkbox) =>
    checkbox.addEventListener("change", filterBooks)
  );

  displayBooks(books);
});

async function fetchLivres() {
  try {
    const response = await fetch("get_livre.php");
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const data = await response.json();

    showToast("Livres chargés avec success", "success");
    return data;
  } catch (error) {
    console.error("Erreur lors de la récupération des livres:", error);
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
