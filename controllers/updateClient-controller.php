<?php
include_once '../db_connection.php';
require_once '../models/client.php';

$database = new Database();
$db = $database->getConnection();
$client = new Client($db);

if (isset($_POST['id'])) {
    $client->id = $_POST['id'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Client ID is missing']);
    exit;
}

$client->prenom = $_POST['prenom'];
$client->nom = $_POST['nom'];
$client->numtel = $_POST['numtel'];
$client->pays = $_POST['pays'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $targetDir = "../resources/clients/{$client->id}/";
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
}

if ($client->update()) {
    echo json_encode(['status' => 'success', 'message' => 'Client updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update client']);
}
?>