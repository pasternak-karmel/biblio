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

$isbn = $_GET['isbn'] ?? null;

if (!$isbn) {
    http_response_code(400);
    echo json_encode(['error' => 'ISBN non fourni']);
    return;
}

try {

    $query = "SELECT l.isbn, l.titre, a.nom AS auteur, l.annee_de_publication, l.genre, e.nom AS editeur, l.langue, l.quantite, COALESCE(em.status, 'disponible') AS stat FROM livre l JOIN auteurs a ON l.auteur = a.id_auteur JOIN editeur e ON l.editeur = e.id_editeur LEFT JOIN emprunts em ON l.isbn = em.ISBN_LIBRE WHERE l.isbn = :isbn ";
    // $stmt = $pdo->prepare($query);
    // $stmt->bindParam(':isbn', $isbn);
    // $book = $stmt->fetch(PDO::FETCH_ASSOC);
    // // error_log("ISBN: $isbn");
    // // error_log("Query: $query");
    // // error_log(print_r($book, true));

    // print_r($book);

    // if ($stmt->execute()) {
    //     echo json_encode($book);
    // } else {
    //     echo json_encode(['error' => 'Livre non trouvé']);
    // }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        echo json_encode($book);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Livre non trouvé']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
}


// print_r($isbn);
// echo "Erreur lors de l'insertion des données.";
// $stmt->execute(['isbn' => $isbn]);
// if ($book) {
    //     echo json_encode($book);
    // } else {
        //     echo json_encode(['error' => 'Livre non trouvé']);
        // }
        
        // $query = " SELECT 
        //         l.titre, 
        //         CONCAT(a.nom, ' ', a.prenom) AS auteur, 
        //         l.annee_de_publication, 
        //         l.genre, 
    //         e.nom AS editeur,
    //         l.langue,
    //         -- l.resume,
    //         COALESCE(em.status, 'disponible') AS status
    //     FROM 
    //         livre l
    //     JOIN 
    //         auteurs a ON l.auteur = a.id_auteur
    //     JOIN 
    //         editeur e ON l.editeur = e.id_editeur
    //     LEFT JOIN 
    //         emprunts em ON l.isbn = em.isbn
    //     WHERE 
    //         l.isbn = :isbn
    // ";