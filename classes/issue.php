<?php
class Issue {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function issue($book_id, $member_id) {
        $stmt = $this->conn->prepare("INSERT INTO issued_books (book_id, member_id, issue_date) VALUES (?, ?, NOW())");
        return $stmt->execute([$book_id, $member_id]);
    }

    public function returnBook($id) {
        $stmt = $this->conn->prepare("SELECT issue_date FROM issued_books WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $issue_date = $row['issue_date'];
        $today = date('Y-m-d');
        $days = (strtotime($today) - strtotime($issue_date)) / (60*60*24);

        $late = ($days > 7) ? 'Y' : 'N';

        $update = $this->conn->prepare("UPDATE issued_books SET return_date = NOW(), returned = 'Y', late_return = ? WHERE id = ?");
        return $update->execute([$late, $id]);
    }

    public function getAllIssued() {
        $sql = "SELECT i.*, b.title, m.name 
                FROM issued_books i 
                JOIN books b ON i.book_id = b.id 
                JOIN members m ON i.member_id = m.id 
                WHERE i.returned = 'N'";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
