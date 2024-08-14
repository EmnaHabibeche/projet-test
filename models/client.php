<?php
class Client {
    private $conn;
    private $table_name = "client";

    public $id;
    public $prenom;
    public $nom;
    public $numtel;
    public $pays;
    public $photo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $checkQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE prenom = :prenom AND nom = :nom AND numtel = :numtel";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':prenom', $this->prenom);
        $checkStmt->bindParam(':nom', $this->nom);
        $checkStmt->bindParam(':numtel', $this->numtel);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Client déjà existe']);
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " (prenom, nom, numtel, pays, photo) VALUES (:prenom, :nom, :numtel, :pays, '')";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':numtel', $this->numtel);
        $stmt->bindParam(':pays', $this->pays);

        if ($stmt->execute()) {
            // Get the last inserted ID
            $this->id = $this->conn->lastInsertId();
            echo json_encode(['status' => 'success', 'message' => 'Client ajouté avec succès']);
            return true;
        }

        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du client']);
        return false;
    }

    public function get() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET prenom = :prenom, nom = :nom, numtel = :numtel, pays = :pays, photo = :photo WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':numtel', $this->numtel);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updatePhoto($id) {
        $query = "UPDATE " . $this->table_name . " SET photo = :photo WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
    public function getClientById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function readSingle() {
        $query = "SELECT id, prenom, nom, numtel, pays, photo FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return $client;
    }

    public function readAll() {
        $query = "SELECT id, prenom, nom, numtel, pays, photo FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $clients;
    }
    
}

?>