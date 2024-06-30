<?php
require_once 'db.php';
$dsn = 'oci:dbname=//localhost:1521/xe';
$username = 'biblio';
$password = 'biblio';


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $name = $_POST['username'];
//     $pass = $_POST['password'];

//     // echo $pass;

//     if (login($name, $pass)) {
//         header("Location: dashboard.html");
//         exit;
//     } else {
//         echo "Invalid username/email or password.";
//         return;
//     }
// } else {
//     die("Method not allowed");
// }


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $response = [
//         'success' => false,
//         'message' => 'Invalid username/email or password.',
//     ];

//     if (login($username, $password)) {
//         $response['success'] = true;
//         $response['message'] = 'Utilisateur connecté avec succès!';
//         header('Content-Type: application/json');
//         echo json_encode($response);
//         exit;
//     } else {
//         header('Content-Type: application/json');
//         echo json_encode($response);
//         exit;
//     }
// } else {
//     http_response_code(405);
//     echo json_encode(['message' => 'Method not allowed']);
// }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = [
        'success' => false,
        'message' => 'Invalid username/email or password.',
        'userId' => null,
    ];

    $userId = login($username, $password);
    if ($userId) {
        $response['success'] = true;
        $response['message'] = 'Utilisateur connecté avec succès!';
        $response['userId'] = $userId;
        $response['username'] = $username;
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
