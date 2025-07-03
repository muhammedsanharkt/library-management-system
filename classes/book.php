<?php
class Book {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($title, $author, $isbn, $copies) {
        if (empty($title) || empty($author) || empty($isbn) || empty($copies)) {
            return "All fields are required.";
        }
        $stmt = $this->conn->prepare("INSERT INTO books (title, author, isbn, copies) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $isbn, $copies]);
    }

    public function getAll($limit, $offset, $search = "") {
        $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["%$search%", "%$search%", $limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($search = "") {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM books WHERE title LIKE ? OR author LIKE ?");
        $stmt->execute(["%$search%", "%$search%"]);
        return $stmt->fetchColumn();
    }
}
?>
