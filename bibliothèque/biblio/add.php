<?php
header('Content-Type: text/html; charset=utf-8');
putenv('NLS_LANG=FRENCH_FRANCE.AL32UTF8');
require_once 'db.php';
$dsn = 'oci:dbname=//localhost:1521/xe';
$username = 'biblio';
$pass = 'biblio';

$pdo = seconnecter($dsn, $username, $pass);

if ($pdo) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'];
        $auteur = $_POST['auteur'];
        $annee = $_POST['annee'];
        $genre = $_POST['genre'];
        $editeur = $_POST['editeur'];
        $langue = $_POST['langue'];
        $disponible = $_POST['qte'];

        // print_r($disponible);

        if (!preg_match("/^[a-zA-Z]+$/", $titre)) {
            die("Le titre du livre ne doit pas contenir des carractères bizzares.");
        }

        $query = "SELECT MAX(isbn) AS max_id FROM livre";
        $result = $pdo->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $newId = $row['MAX_ID'] + 1;

        $sql = "INSERT INTO livre (isbn, titre, auteur, annee_de_publication, genre, editeur, langue, quantite) VALUES (:isbn, :titre, :auteur, :annee_de_publication, :genre, :editeur, :langue, :quantite)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':isbn', $newId);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':auteur', $auteur);
        $stmt->bindParam(
            ':annee_de_publication',
            $annee,
        );
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':editeur', $editeur);
        $stmt->bindParam(':langue', $langue);
        $stmt->bindParam(':quantite', $disponible);

        if ($stmt->execute()) {
            echo "Les données ont été insérées avec succès.";
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Erreur lors de l'insertion des données." . $errorInfo[2];;
        }
    } else {
        die("La requête n'est pas une requête POST.");
    }
} else {
    die("La connexion à la base de données a échoué.");
}





// if ($phone < 40000000 || $phone>= 99999999) {
    // die("Votre numéro n'est pas béninois");
    // }
    // if (!preg_match("/^[a-zA-Z]+$/", $auteur)) {
    //     die("L'auteur ne doit pas contenir des carractères bizzares.");
    // }
    // if (!preg_match("/^[a-zA-Z]+$/", $editeur)) {
    //     die("L'éditeur ne doit pas contenir des carractères bizzares.");
    // }