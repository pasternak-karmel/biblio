<?php
require_once 'db.php';
$dsn = 'oci:dbname=//localhost:1521/xe';
$username = 'biblio';
$pass = 'biblio';

$pdo = seconnecter($dsn, $username, $pass);

if ($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // if($password)
        // {

        // }

        $check = "SELECT * FROM member WHERE email = :email OR username = :username";
        $stmt = $pdo->prepare($check);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            die("Cet utilisateur existe déjà.");
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = "SELECT MAX(id_user) AS max_id FROM member";
        $result = $pdo->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $newId = $row['MAX_ID'] + 1;

        $sql = "INSERT INTO member (id_user, username, email, password) VALUES (:id_user, :username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_user', $newId);
        $stmt->bindParam(':username', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        // $stmt->bindParam(':status', 'N');

        if ($stmt->execute()) {
            echo "Les données ont été insérées avec succès.";
        } else {
            echo "Erreur lors de l'insertion des données.";
        }
    } else {
        die("La requête n'est pas une requête POST.");
    }
} else {
    die("La connexion à la base de données a échoué.");
}




// if (!preg_match("/^[a-zA-Z]+$/", $name)) {
// die("Votre nom ne doit pas contenir des carractères bizzares.");
// }

// if (!preg_match("/^[a-zA-Z]+$/", $prenom)) {
// die("Votre prenom ne doit pas contenir des carractères bizzares.");
// }

// if ($phone < 40000000 || $phone>= 99999999) {
    // die("Votre numéro n'est pas béninois");
    // }