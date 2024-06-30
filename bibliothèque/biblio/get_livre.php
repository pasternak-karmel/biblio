<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';
function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

function getAllLivre()
{
    $dsn = 'oci:dbname=//localhost:1521/xe';
    $username = 'biblio';
    $pass = 'biblio';
    $pdo = seconnecter($dsn, $username, $pass);

    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion à la bd']);
        return;
    }

    try {

        $karmel = "SELECT isbn, titre, auteur,annee_de_publication, genre,editeur, langue FROM livre";

        // $query = "SELECT l.titre, CONCAT(a.nom, ' ', a.prenom) AS auteur, l.annee_de_publication, l.genre, e.nom AS editeur, l.langue FROM livre l JOIN auteurs a ON l.auteur = a.id_auteur JOIN editeur e ON l.editeur = e.id_editeur ";


        $query = "SELECT l.isbn, l.titre, a.nom AS auteur, l.annee_de_publication, l.genre, e.nom AS editeur, l.langue FROM livre l 
        JOIN auteurs a ON l.auteur = a.id_auteur
        JOIN editeur e ON l.editeur = e.id_editeur 
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $livre = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($livre);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    }
}

getAllLivre();

// $karmel = "SELECT * FROM emprunts WHERE status = 'emprunté' AND DATE_ADD(date_emprunt, INTERVAL 14 DAY) < CURRENT_DATE";