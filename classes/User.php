<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        if ($this->conn === null) {
            die('Database connection failed');
        }
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password first, as stated in the requirements
        $query = 'INSERT INTO ' . $this->table . ' (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword); // Use the hashed password variable

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($username, $password) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) { // Verifying the password during login process, as stated in the requirements 
                return $user['id']; // Login successful
            } else {
                return 'incorrect_password'; // Incorrect password
            }
        } else {
            return 'username_not_found'; // Username does not exist
        }
    }
    
     // Method to fetch user information by user_id
    public function getUserById($user_id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function isUsernameTaken($username) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function isEmailTaken($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
?>
