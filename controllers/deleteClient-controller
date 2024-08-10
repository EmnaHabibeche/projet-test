<?php
include_once '../db_connection.php';

require_once '../models/Client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $client = new Client($db);

    $client->id = $_POST['id'];

    if ($client->delete()) {
        echo json_encode(['status' => 'success', 'message' => 'Client deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting client']);
    }
}
?>
