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

$idMembre = $_GET['id'] ?? null;

if (!$idMembre) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de membre non fourni']);
    return;
}

try {
    $query = "SELECT l.isbn, l.titre, a.nom AS auteur, l.annee_de_publication, l.genre, e.nom AS editeur, l.langue, em.date_emprunt, em.date_retour, em.status 
    FROM emprunts em
    JOIN livre l ON em.isbn_libre = l.isbn
    JOIN auteurs a ON l.auteur = a.id_auteur
    JOIN editeur e ON l.editeur = e.id_editeur
    WHERE em.id_membre = :id_membre";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_membre', $idMembre);
    $stmt->execute();
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($emprunts) {
        echo json_encode($emprunts);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Livre non trouvé']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des emprunts : ' . $e->getMessage()]);
}
