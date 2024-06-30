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
function getAllAuteurs()
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
        $query = "SELECT id_auteur, nom, prenom, nationalite FROM auteurs";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($auteurs);
        // print_r($auteurs);
        // $auteurs = utf8ize($auteurs);

        // $json = json_encode($auteurs);
        // if ($json === false) {
        //     $json = json_encode(["jsonError" => json_last_error_msg()]);
        //     if ($json === false) {
        //         $json = '{"jsonError":"unknown"}';
        //     }
        //     http_response_code(500);
        // }
        // echo $json;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
    }
}

getAllAuteurs();
