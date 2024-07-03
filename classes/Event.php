<?php
require_once 'Database.php';

class Event {
    private $conn;
    private $table = 'events';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        if ($this->conn === null) {
            die('Database connection failed');
        }
    }

    public function create($user_id, $event_name, $description, $date_time, $location) {
        $query = 'INSERT INTO ' . $this->table . ' (user_id, event_name, description, date_time, location) VALUES (:user_id, :event_name, :description, :date_time, :location)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_time', $date_time);
        $stmt->bindParam(':location', $location);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($user_id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($event_id, $event_name, $description, $date_time, $location) {
        $query = 'UPDATE ' . $this->table . ' SET event_name = :event_name, description = :description, date_time = :date_time, location = :location WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $event_id);
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_time', $date_time);
        $stmt->bindParam(':location', $location);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($event_id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $event_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
