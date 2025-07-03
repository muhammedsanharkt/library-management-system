<?php
class Member {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($name, $email, $phone) {
        if (empty($name) || empty($email) || empty($phone)) {
            return "All fields are required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
        $stmt = $this->conn->prepare("INSERT INTO members (name, email, phone) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $phone]);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM members");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
