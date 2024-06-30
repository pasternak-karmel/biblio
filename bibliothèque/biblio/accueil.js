document.addEventListener("DOMContentLoaded", () => {
  const bookForm = document.getElementById("bookForm");
  const bookList = document.getElementById("bookList");

  bookForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const title = document.getElementById("title").value;
    const author = document.getElementById("author").value;
    const status = document.getElementById("status").value;

    addBook(title, author, status);

    bookForm.reset();
  });

  function addBook(title, author, status) {
    const li = document.createElement("li");
    const statusClass = getStatusClass(status);
    li.innerHTML = `
            <span>${title} par ${author}</span>
            <span class="book-status ${statusClass}">${status}</span>
            <button onclick="removeBook(this)">Supprimer</button>
        `;
    bookList.appendChild(li);
  }

  function getStatusClass(status) {
    if (status === "emprunté") return "status-emprunte";
    if (status === "en retard") return "status-en-retard";
    if (status === "retourné") return "status-retourne";
    return "";
  }

  window.removeBook = function (button) {
    const li = button.parentElement;
    bookList.removeChild(li);
  };
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
