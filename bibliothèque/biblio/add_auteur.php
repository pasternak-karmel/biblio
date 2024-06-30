<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';
$dsn = 'oci:dbname=//localhost:1521/xe';
$username = 'biblio';
$pass = 'biblio';


$pdo = seconnecter($dsn, $username, $pass);

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la bd']);
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nationnalite = $_POST['nationnalite'];

    try {

        if (!preg_match("/^[a-zA-Z]+$/", $nom)) {
            die("Votre nom ne doit pas contenir des carractères bizzares.");
        }

        if (!preg_match("/^[a-zA-Z]+$/", $nationnalite)) {
            die("Votre nationnalité ne doit pas contenir des carractères bizzares.");
        }

        $checkQuery = "SELECT COUNT(*) AS count FROM auteurs WHERE nom = :nom AND prenom = :prenom AND nationalite = :nationalite";
        $stmtcheck = $pdo->prepare($checkQuery);
        $stmtcheck->bindParam(':nom', $nom);
        $stmtcheck->bindParam(':prenom', $prenom);
        $stmtcheck->bindParam(':nationalite', $nationnalite);
        $row = $stmtcheck->fetch(PDO::FETCH_ASSOC);

        // if ($row['count'] > 0) {
        //     die("L'auteur existe déjà.");
        // }

        $query = "SELECT MAX(id_auteur) AS max_id FROM auteurs";
        $result = $pdo->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $newId = $row['MAX_ID'] + 1;

        $sql = "INSERT INTO auteurs (id_auteur, nom, prenom, nationalite) VALUES (:id_auteur, :nom, :prenom, :nationalite)";

        $stmt = $pdo->prepare($sql);

        // $stmt = $pdo->prepare("INSERT INTO auteurs (id_auteur, nom, prenom, nationalite) VALUES (:id, :nom, :prenom, :nationalite)");

        $stmt->bindParam(':id_auteur', $newId);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nationalite', $nationnalite);

        if ($stmt->execute()) {
            echo "Les données ont été insérées avec succès.";
        } else {
            echo "Erreur lors de l'insertion des données.";
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['Pdo erreur' => 'Erreur de connexion : ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}



        // $stmt->execute(['id' => $newId, 'nom' => $nom, 'prenom' => $prenom, 'nationalite' => $nationnalite]);
        // if ($stmt->rowCount() > 0) {
        //     echo json_encode(['succes' => true, 'message' => "L'auteur a été ajouté avec succès."]);
        // } else {
        //     echo json_encode(['succes' => false, 'message' => "Une erreur est survenue lors de l'ajout de l'auteur."]);
        // }