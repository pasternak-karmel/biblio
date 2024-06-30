document.addEventListener("DOMContentLoaded", () => {
  const signUpButton = document.getElementById("signUp");
  const signInButton = document.getElementById("signIn");
  const container = document.querySelector(".container");

  signUpButton.addEventListener("click", () => {
    container.classList.add("right-panel-active");
  });

  signInButton.addEventListener("click", () => {
    container.classList.remove("right-panel-active");
  });

  const signUpForm = document.getElementById("signUpForm");
  const signInForm = document.getElementById("signInForm");

  signUpForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const username = document.getElementById("signUpUsername").value;
    const email = document.getElementById("signUpEmail").value;
    const password = document.getElementById("signUpPassword").value;

    let data = {
      username: username,
      email: email,
      password: password,
    };

    fetch("inscription.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(data),
    })
      .then((response) => response.text())
      .then((data) => {
        showToast(data, "success");
        document.getElementById("signUpForm").reset();
      })
      .catch((error) => {
        console.error("Error:", error);
        showToast(data, "Erreur");
        // alert("An error occurred: " + error);
      });
  });

  signInForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const username = document.getElementById("signInUsername").value;
    const password = document.getElementById("signInPassword").value;

    let data = {
      username: username,
      password: password,
    };

    fetch("connexion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showToast("Utilisateur connecté avec succès!", "success");
          console.log("Succes", data);
          document.getElementById("signUpForm").reset();
          window.location.href =
            data.username === "karmelavenon@gmail.com"
              ? "dashboard.html"
              : "main.html";
              localStorage.setItem("userId", data.userId);
          return;
        }
        showToast(data, "error");
        console.log(data);
      })
      .catch((error) => {
        console.log("Error:", error);
        showToast(error, "error");
      });
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
