<?php
// Load classes
require_once 'classes/Database.php';
require_once 'classes/Issue.php';

// Connect DB
$db = new Database();
$conn = $db->connect();
$issue = new Issue($conn);

$message = "";

// Handle return
if (isset($_GET['return_id'])) {
    $return_id = intval($_GET['return_id']);

    if ($return_id > 0) {
        if ($issue->returnBook($return_id)) {
            $message = '<div class="alert alert-success">✅ Book returned successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Failed to return book.</div>';
        }
    }
}

// Get all issued books not yet returned
$issued = $issue->getAllIssued();
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">🔙 Return Issued Books</h2>

<?php echo $message; ?>

<?php if (count($issued) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Issue Date</th>
                    <th>Return</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($issued as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['issue_date']; ?></td>
                        <td>
                            <a href="return_book.php?return_id=<?php echo $row['id']; ?>"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Are you sure to return this book?');">
                                ✅ Return Book
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">📚 No books are currently issued.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
