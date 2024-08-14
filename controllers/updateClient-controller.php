<?php
include_once '../db_connection.php';
require_once '../models/Client.php';

$database = new Database();
$db = $database->getConnection();
$client = new Client($db);

if (isset($_POST['id'])) {
    $client->id = $_POST['id'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Client ID is missing']);
    exit;
}

$client->id = isset($_POST['id']) ? $_POST['id'] : null;
$client->prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null;
$client->nom = isset($_POST['nom']) ? $_POST['nom'] : null;
$client->numtel = isset($_POST['numtel']) ? $_POST['numtel'] : null;
$client->pays = isset($_POST['pays']) ? $_POST['pays'] : null;

// Check if a new photo was uploaded
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $targetDir = "../resources/clients/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($_FILES['photo']['name']);
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $client->photo = str_replace('../', '', $targetFile); // Store relative path
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload photo']);
        exit;
    }
} else if (isset($_POST['existing_photo'])) {
    $client->photo = $_POST['existing_photo'];
} else {
    $client->photo = null;
}

if ($client->update()) {
    echo json_encode(['status' => 'success', 'message' => 'Client updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update client']);
}
?>