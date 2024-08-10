<?php
include_once '../db_connection.php';
require_once '../models/Client.php';

$database = new Database();
$db = $database->getConnection();
$client = new Client($db);

$stmt = $client->get();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($clients);
?>
