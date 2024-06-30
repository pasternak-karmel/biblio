<?php
require_once 'db_connect.php';

$dsn = 'oci:dbname=//localhost:1521/xe';
$username = 'biblio';
$password = 'biblio';

$pdo = seconnecter($dsn, $username, $password);

if ($pdo) {

    //je vais faire avec l'option filtrer comme un select
    $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
    if ($filter) {
        $sql = "SELECT * FROM livre WHERE titre LIKE :filter OR annee_de_publication LIKE :filter";
        $stmt = $pdo->prepare($sql);
        $filterParam = '%' . $filter . '%';
        $stmt->bindParam(':filter', $filterParam);
    } else {
        $sql = "SELECT * FROM livre";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($employes as $employe) {
        echo '<tr>';
        echo '<td>' . $employe['TITRE'] . '</td>';
        echo '<td>' . $employe['AUTEUR'] . '</td>';
        echo '<td>' . $employe['ANNEE_DE_PUBLICATION'] . '</td>';
        echo '<td>' . $employe['GENRE'] . '</td>';
        echo '<td>' . $employe['EDITEUR'] . '</td>';
        echo '<td>' . $employe['LANGUE'] . '</td>';
        echo '</tr>';
    }
} else {
    die("Veuillez vérifiez si la connexion avec la db est ok :)");
}
