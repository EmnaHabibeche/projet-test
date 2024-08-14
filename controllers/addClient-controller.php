<?php
require_once '../db_connection.php';
require_once '../models/client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $client = new Client($db);

    $client->prenom = htmlspecialchars($_POST['prenom']);
    $client->nom = htmlspecialchars($_POST['nom']);
    $client->numtel = htmlspecialchars($_POST['numtel']);
    $client->pays = htmlspecialchars($_POST['pays']);
    $client->photo = uploadPhoto($_FILES['photo']);

    if ($client->create()) {
        echo json_encode(['status' => 'success', 'message' => 'Client added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding client']);
    }
}

function uploadPhoto($file) {
    $target_dir = "../resources/clients/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file;
}
?>

