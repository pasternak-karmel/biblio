<?php
header('Content-Type: text/html; charset=utf-8');
putenv('NLS_LANG=FRENCH_FRANCE.AL32UTF8');
function seconnecter($dsn, $username, $password)
{
    try {
        if (!extension_loaded('pdo_oci') && !extension_loaded('oci8')) {
            die("L'extension PDO_OCI ou OCI8 n'est pas chargée dans PHP.");
        }
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        return false;
    }
}

// function login($username, $password)
// {
//     $dsn = 'oci:dbname=//localhost:1521/xe';
//     $username = 'biblio';
//     $pass = 'biblio';
//     $pdo = seconnecter($dsn, $username, $pass);

//     if (!$pdo) {
//         echo "Erreur de connexion à la bd";
//         return false;
//     }
//     try {

//         // $nom = "karmel04";
//         // $mdp = "moiKarmel";

//         $check = "SELECT * FROM member WHERE email = :email or username = :username";
//         $stmt = $pdo->prepare($check);
//         $stmt->bindParam(':email', $username);
//         $stmt->bindParam(':username', $username);
//         $stmt->execute();


//         $user = $stmt->fetch(PDO::FETCH_ASSOC);
//         // echo "The password of user is " . $user['password'];


//         echo $user['password'];
//     } catch (PDOException $e) {
//         die("Login failed: " . $e->getMessage());
//     }
// }

// function login($username, $password)
// {
//     $dsn = 'oci:dbname=//localhost:1521/xe';
//     $dbUser = 'biblio';
//     $dbPass = 'biblio';
//     $pdo = seconnecter($dsn, $dbUser, $dbPass);

//     if (!$pdo) {
//         return false;
//     }

//     try {
//         $check = "SELECT * FROM member WHERE email = :email OR username = :username";
//         $stmt = $pdo->prepare($check);
//         $stmt->bindParam(':email', $username);
//         $stmt->bindParam(':username', $username);
//         $stmt->execute();
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);
//         // print_r($user['ID_USER']);
//         if ($user && password_verify($password, $user['PASSWORD'])) {
//             return true;
//         }

//         // if ($user) {
//         //     if ($user['PASSWORD'] === $password) {
//         //         return true;
//         //         // return $user['ID_USER'];
//         //     }
//         // }
//     } catch (PDOException $e) {
//         return false;
//     }

//     return false;
// }

function login($username, $password)
{
    $dsn = 'oci:dbname=//localhost:1521/xe';
    $dbUser = 'biblio';
    $dbPass = 'biblio';
    $pdo = seconnecter($dsn, $dbUser, $dbPass);

    if (!$pdo) {
        return false;
    }

    try {
        $check = "SELECT * FROM member WHERE email = :email OR username = :username";
        $stmt = $pdo->prepare($check);
        $stmt->bindParam(':email', $username);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['PASSWORD'])) {
            return $user['ID_USER'];
        }
    } catch (PDOException $e) {
        return false;
    }

    return false;
}

function addAuteur($nom, $prenom, $nationnalite)
{
    $dsn = 'oci:dbname=//localhost:1521/xe';
    $username = 'biblio';
    $pass = 'biblio';
    $pdo = seconnecter($dsn, $username, $pass);

    if (!$pdo) {
        echo "Erreur de connexion à la bd";
        return false;
    }
    try {
        $query = "SELECT MAX(id_auteur) AS max_id FROM auteurs";
        $result = $pdo->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $newId = $row['MAX_ID'] + 1;

        $stmt = $pdo->prepare("INSERT INTO auteurs VALUES (:id,:nom,:prenom, :nationnalite)");
        $stmt->execute(['id' => $newId, 'nom' => $nom, 'prenom' => $prenom, 'nationnalite' => $nationnalite]);

        if ($stmt->execute()) {
            echo "L'auteur a été ajouté avec succès.";
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Erreur lors de l'insertion des données." . $errorInfo[2];
            return false;
        }
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function getALlAuteur()
{
    $dsn = 'oci:dbname=//localhost:1521/xe';
    $username = 'biblio';
    $pass = 'biblio';
    $pdo = seconnecter($dsn, $username, $pass);

    if (!$pdo) {
        echo "Erreur de connexion à la bd";
        return false;
    }

    try {
        $query = "SELECT id_auteur, nom, prenom, nationalite FROM auteurs";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $auteurs;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
function getALlEditeur()
{
    $dsn = 'oci:dbname=//localhost:1521/xe';
    $username = 'biblio';
    $pass = 'biblio';
    $pdo = seconnecter($dsn, $username, $pass);

    if (!$pdo) {
        echo "Erreur de connexion à la bd";
        return false;
    }

    try {
        $query = "SELECT id_editeur, nom, prenom, nationalite FROM editeur";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $editeur = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $editeur;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// if ($stmt->rowCount() == 1) {
// if (password_verify($password, $user['password'])) {
//     return true;
// } else {
//     die("The password of user is incorrect");
//     return false;
// }
// } else {
//     die('User not found!!');
//     return false;
// }
            // $stmt = $pdo->prepare("SELECT * FROM member WHERE username = :username OR email = :email");
            // $stmt->execute(['username' => $username, 'email' => $username]);