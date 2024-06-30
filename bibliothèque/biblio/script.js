document.addEventListener("DOMContentLoaded", () => {
  const bookForm = document.getElementById("bookForm");
  // const bookList = document.getElementById("bookList");
  fetchAuteurs();
  fetchEditeur();

  bookForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const title = document.getElementById("title").value;
    const author = document.getElementById("auteur").value;
    const annee = document.getElementById("annee").value;
    const genre = document.getElementById("genre").value;
    const qte = document.getElementById("qte").value;
    const editeur = document.getElementById("editeur").value;
    const langue = document.getElementById("langue").value;
    let data = {
      titre: title,
      auteur: author,
      annee: annee,
      genre: genre,
      qte: qte,
      editeur: editeur,
      langue: langue,
    };
    fetch("add.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(data),
    })
      .then((response) => response.text())
      .then((data) => {
        console.log("Success:", data);
        showToast(data, "success");
      })
      .catch((error) => {
        console.error("Error:", error);
        alert(`An error occurred: ${error}`);
      });

    bookForm.reset();
  });

  async function fetchAuteurs() {
    try {
      const response = await fetch("get_auteurs.php");
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const auteurSelect = document.getElementById("auteur");
      const auteurs = await response.json();

      auteurs.forEach((auteur) => {
        const option = document.createElement("option");
        option.value = auteur.ID_AUTEUR;
        option.textContent = `${auteur.PRENOM} ${auteur.NOM} (${auteur.NATIONALITE})`;
        auteurSelect.appendChild(option);
      });
    } catch (error) {
      console.error("Erreur lors de la récupération des auteurs:", error);
    }
  }

  async function fetchEditeur() {
    try {
      const response = await fetch("get_editeur.php");
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const auteurSelect = document.getElementById("editeur");
      const auteurs = await response.json();

      auteurs.forEach((auteur) => {
        const option = document.createElement("option");
        option.value = auteur.ID_EDITEUR;
        option.textContent = `${auteur.NOM} ${auteur.ADRESSE} (${auteur.PAYS})`;
        auteurSelect.appendChild(option);
      });
    } catch (error) {
      console.error("Erreur lors de la récupération des editeurs:", error);
    }
  }

  // window.removeBook = function (button) {
  //   const li = button.parentElement;
  //   bookList.removeChild(li);
  // };
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

// function addBook(title, author, annee, genre, editeur, langue) {
//   const li = document.createElement("li");
//   li.innerHTML = `
//           <span>${title} by ${author} ${annee} ${genre}, ${editeur} ${langue}</span>
//           <button onclick="removeBook(this)">Affiché</button>
//           <button onclick="removeBook(this)">Supprimer</button>
//       `; // Ne marche pas encore
//   bookList.appendChild(li);
// }

// fetch("get_auteurs.php", {
//   method: "POST",
//   headers: {
//     "Content-Type": "application/json; charset=utf-8",
//   },
// })
//   .then((response) => response.text())
//   .then((data) => {
//     console.log("Success:", data);
//     const auteurSelect = document.getElementById("auteur");

//     data.forEach((auteur) => {
//       const option = document.createElement("option");
//       option.value = auteur.id_auteur;
//       option.textContent = `${auteur.prenom} ${auteur.nom} (${auteur.nationalite})`;
//       auteurSelect.appendChild(option);
//     });
//     alert(data);
//   })
//   .catch((error) => {
//     console.error("Error:", error);
//     alert(`An error occurred: ${error}`);
//   });

// fetch("get_auteurs.php", {
//   method: "POST",
//   headers: {
//     "Content-Type": "application/json; charset=utf-8",
//   },
// })
//   .then((response) => {
//     if (!response.ok) {
//       throw new Error(`HTTP error! Status: ${response.status}`);
//     }
//     return response.json();
//   })
//   .then((data) => {
//     console.log("Success:", data);

//     const auteurSelect = document.getElementById("auteur");

//     if (Array.isArray(data)) {
//       data.forEach((auteur) => {
//         const option = document.createElement("option");
//         option.value = auteur.ID_AUTEUR;
//         option.textContent = `${auteur.PRENOM} ${auteur.NOM} (${auteur.NATIONALITE})`;
//         auteurSelect.appendChild(option);
//       });
//     } else {
//       console.error("Error: Data received is not an array");
//     }

//   })
//   .catch((error) => {
//     console.error("Error:", error);
//     alert(`An error occurred: ${error}`);
//   });
