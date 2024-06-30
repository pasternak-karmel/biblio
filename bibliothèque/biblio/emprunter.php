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

$data = json_decode(file_get_contents('php://input'), true);
$isbn = $data['isbn'] ?? null;
$returnDate = $data['returnDate'] ?? null;
$id = $data['id'] ?? null;

if (!$isbn || !$returnDate || !$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Données d\'emprunt invalides']);
    return;
}

try {
    $pdo->beginTransaction();

    $query = "SELECT MAX(id_emprunts) AS max_id FROM emprunts";
    $result = $pdo->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $newId = $row['MAX_ID'] + 1;

    $insert = "INSERT INTO emprunts (id_emprunts, id_membre, isbn_libre, date_emprunt, date_retour, status) VALUES (:id_emprunts, :id_membre, :isbn_libre, SYSDATE, TO_DATE(:date_retour, 'YYYY-MM-DD'), :status)";
    $stmt = $pdo->prepare($insert);
    $stmt->bindParam(':id_emprunts', $newId);
    $stmt->bindParam(':id_membre', $id);
    $stmt->bindParam(':isbn_libre', $isbn);
    $stmt->bindParam(':date_retour', $returnDate);
    $stmt->bindParam(':status', "emprunte");
    $stmt->execute();

    $update = "UPDATE livre SET quantite = quantite - 1 WHERE isbn = :isbn";
    $stmt = $pdo->prepare($update);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->execute();

    $pdo->commit();

    // echo json_encode(['success' => 'Emprunt effectué avec succès']);
    echo json_encode('Emprunt effectué avec succès');
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'emprunt : ' . $e->getMessage()]);
}

// $insert = "INSERT INTO emprunts (id_emprunts, id_membre, isbn_libre, date_emprunt, date_retour, status) VALUES (:id_emprunts, :id_membre, SYSDATE, TO_DATE(:returnDate, 'YYYY-MM-DD'))";