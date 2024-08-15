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
            $query = "INSERT INTO client (prenom, nom, numtel, pays) VALUES (:prenom, :nom, :numtel, :pays)";
            $stmt = $this->conn->prepare($query);
        
            // Bind parameters
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':numtel', $this->numtel);
            $stmt->bindParam(':pays', $this->pays);
        
            // Execute the query
            try {
                if ($stmt->execute()) {
                    $this->id = $this->conn->lastInsertId();
                    return $this->id;
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                return false;
            }
        
            return false;
        }

        public function clientExists() {
            $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE prenom = :prenom AND nom = :nom AND numtel = :numtel";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':numtel', $this->numtel);
            
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        }
        public function uploadPhoto($file, $clientId) {
            // Define the upload directory
            $target_dir = "../resources/clients/" . $clientId . "/";
        
            // Create the directory if it doesn't exist
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    error_log("Failed to create directory: " . $target_dir);
                    return null;
                }
                error_log("Directory created: " . $target_dir);
            }
        
            // Define the target file path
            $target_file = $target_dir . basename($file["name"]);
        
            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                error_log("File moved to: " . $target_file);
                // Return the relative path to store in the database
                return "resources/clients/" . $clientId . "/" . basename($file["name"]);
            } else {
                error_log("Failed to move uploaded file to: " . $target_file);
                return null;
            }
        }
        
        public function updatePhoto($clientId) {
            $query = "UPDATE client SET photo = :photo WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':photo', $this->photo);
            $stmt->bindParam(':id', $clientId);
            return $stmt->execute();
        }

        

        public function update() {
            $query = "UPDATE " . $this->table_name . " 
                      SET prenom = :prenom, nom = :nom, numtel = :numtel, pays = :pays";
            
            // Only include photo in the update if it's set
            if ($this->photo) {
                $query .= ", photo = :photo";
            }
            
            $query .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->bindParam(':prenom', $this->prenom);
            $stmt->bindParam(':nom', $this->nom);
            $stmt->bindParam(':numtel', $this->numtel);
            $stmt->bindParam(':pays', $this->pays);
            $stmt->bindParam(':id', $this->id);
            
            if ($this->photo) {
                $stmt->bindParam(':photo', $this->photo);
            }
        
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (PDOException $e) {
                error_log("Update error: " . $e->getMessage());
            }
            return false;
        }

        public function delete() {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id);
            
            if ($stmt->execute()) {
                // Delete the client's folder
                $clientFolder = "../resources/clients/" . $this->id;
                if (is_dir($clientFolder)) {
                    $this->removeDirectory($clientFolder);
                }
                return true;
            }
            return false;
        }
        
        private function removeDirectory($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (is_dir($dir . "/" . $object)) {
                            $this->removeDirectory($dir . "/" . $object);
                        } else {
                            unlink($dir . "/" . $object);
                        }
                    }
                }
                rmdir($dir);
            }
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
        
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function readAll() {
            $query = "SELECT id, prenom, nom, numtel, pays, photo FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }  
    
        
        
        
    }


    
    


?>