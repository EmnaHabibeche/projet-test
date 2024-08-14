<?php
include_once '../db_connection.php';

require_once '../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $client = new Client($db);

    $client->id = $_POST['id'];
    $client->prenom = htmlspecialchars($_POST['prenom']);
    $client->nom = htmlspecialchars($_POST['nom']);
    $client->numtel = htmlspecialchars($_POST['numtel']);
    $client->pays = htmlspecialchars($_POST['pays']);
    $client->photo = uploadPhoto($_FILES['photo']);

    if ($client->update()) {
        echo json_encode(['status' => 'success', 'message' => 'Client updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating client']);
    }
}

function uploadPhoto($file) {
    $target_dir = "../ressources/clients/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file;
}
?>
