<?php


include_once '../db_connection.php';
require_once '../models/client.php';

$database = new Database();
$db = $database->getConnection();
$client = new Client($db);


if (isset($_GET['id'])) {
    $client->id = $_GET['id'];
    $result = $client->readSingle();
} else {
    $result = $client->readAll();
}


echo json_encode($result);
?>
