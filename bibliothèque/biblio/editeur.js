document
  .getElementById("auteurForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const nom = document.getElementById("nom").value;
    const adresse = document.getElementById("adresse").value;
    const pays = document.getElementById("pays").value;

    let data = {
      nom: nom,
      adresse: adresse,
      pays: pays,
    };

    fetch("add_editeur.php", {
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
        // alert(data);
      })
      .catch((error) => {
        console.error("Error:", error);
        showToast(error, "error");
        // alert("An error occurred: " + error);
      });

    // async function AddAuteurs() {
    //   try {
    //     const response = await fetch("add_auteur.php", {
    //       method: "POST",
    //       headers: {
    //         // "Content-Type": "application/json; charset=utf-8",
    //         "Content-Type": "application/x-www-form-urlencoded",
    //       },
    //       //   body: JSON.stringify({
    //       //     nom: nom,
    //       //     prenom: prenom,
    //       //     nationnalite: nationnalite,
    //       //   }),
    //       body: new URLSearchParams(data),
    //     });
    //     if (!response.ok) {
    //       throw new Error("Network response was not ok");
    //     } else {
    //       alert("Auteur ajouté avec succès");
    //       document.getElementById("auteurForm").reset();
    //     }
    //     // const auteurs = await response.json();

    //     // if (auteurs.succes) {
    //     //   alert(auteurs.message);
    //     //   document.getElementById("auteurForm").reset();
    //     // } else {
    //     //   alert(auteurs.message);
    //     //   return;
    //     // }
    //   } catch (error) {
    //     console.error("Erreur lors de l'ajouts de  l'auteur:", error);
    //   }
    // }
    // AddAuteurs();
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

// const li = document.createElement("li");
// li.textContent = `Nom: ${nom}, Adresse: ${adresse}, Nationnalité: ${nationnalite}`;

// document.getElementById("AuteurList").appendChild(li);
