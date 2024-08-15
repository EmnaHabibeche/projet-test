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

    // Create client without photo first
    $newClientId = $client->create();
    
    if ($newClientId) {
        // Process photo upload if client creation was successful
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $client->uploadPhoto($_FILES['photo'], $newClientId);
            if ($photoPath) {
                $client->photo = $photoPath;
                $client->id = $newClientId;
                if ($client->updatePhoto($newClientId)) {
                    echo json_encode(['status' => 'success', 'message' => 'Client added successfully with photo']);
                } else {
                    echo json_encode(['status' => 'warning', 'message' => 'Client added but photo update failed']);
                }
            } else {
                echo json_encode(['status' => 'warning', 'message' => 'Client added but photo upload failed']);
            }
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Client added successfully without photo']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error creating client']);
    }
}
?>