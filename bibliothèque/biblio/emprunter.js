document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const isbn = parseInt(params.get("isbn"), 10);

  document
    .getElementById("borrow-form")
    .addEventListener("submit", async (event) => {
      event.preventDefault();
      const id = localStorage.getItem("userId");
      const returnDate = document.getElementById("return-date").value;
      try {
        const response = await fetch("emprunter.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ isbn, returnDate, id: id }),
        });
        const result = await response.json();
        if (response.ok) {
          showToast("Emprunt effectué avec succès", "success");
          window.location.href = "main.html";
        } else {
          showToast(result.error, "erreur");
        }
      } catch (error) {
        console.error("Erreur lors de l'emprunt du livre:", error);
        showToast("Erreur lors de l'emprunt du livre", "erreur");
      }
    });
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
