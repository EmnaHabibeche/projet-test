<?php
require_once '../db_connection.php';
require_once '../models/client.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $client = new Client($db);

    $client->prenom = htmlspecialchars($_POST['prenom']);
    $client->nom = htmlspecialchars($_POST['nom']);
    $client->numtel = htmlspecialchars($_POST['numtel']);
    $client->pays = htmlspecialchars($_POST['pays']);

    // Initialize photo path
    $client->photo = null;

    // Process photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoPath = uploadPhoto($_FILES['photo']);
        if ($photoPath) {
            $client->photo = $photoPath;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Photo upload failed']);
            exit;
        }
    }
    if ($client->create()) {
        echo json_encode(['status' => 'success', 'message' => 'Client added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error creating client']);
    }
}


?>


