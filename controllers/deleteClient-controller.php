<?php
include_once '../db_connection.php';
require_once '../models/client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $client = new Client($db);
    
    $client->id = $_POST['id'];
    
    if ($client->delete()) {
        echo json_encode(['status' => 'success', 'message' => 'Client and associated folder deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting client or associated folder']);
    }
}
?>