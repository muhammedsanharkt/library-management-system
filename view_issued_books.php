<?php
require_once 'classes/Database.php';
require_once 'classes/Issue.php';

// Connect DB
$db = new Database();
$conn = $db->connect();
$issue = new Issue($conn);

// Fetch all issued books (including returned)
$sql = "SELECT i.*, b.title, m.name 
        FROM issued_books i 
        JOIN books b ON i.book_id = b.id 
        JOIN members m ON i.member_id = m.id 
        ORDER BY i.issue_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$issued_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">📚 All Issued Books</h2>

<?php if (count($issued_books) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Returned</th>
                    <th>Late Return</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($issued_books as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['issue_date']; ?></td>
                        <td><?php echo $row['return_date'] ?? '<span class="text-muted">-- Not Returned --</span>'; ?></td>
                        <td>
                            <?php if ($row['returned'] === 'Y'): ?>
                                <span class="badge bg-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['late_return'] === 'Y'): ?>
                                <span class="badge bg-danger">Late</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No issued books found.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
