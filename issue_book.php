<?php
// Load classes
require_once 'classes/Database.php';
require_once 'classes/Book.php';
require_once 'classes/Member.php';
require_once 'classes/Issue.php';

// Connect DB
$db = new Database();
$conn = $db->connect();

$book = new Book($conn);
$member = new Member($conn);
$issue = new Issue($conn);

$message = "";

// Fetch books & members for dropdowns
$books = $book->getAll(1000, 0); // get all books, no pagination here
$members = $member->getAll();

// Handle form submit
if (isset($_POST['issue_book'])) {
    $book_id = intval($_POST['book_id']);
    $member_id = intval($_POST['member_id']);

    if ($book_id <= 0 || $member_id <= 0) {
        $message = '<div class="alert alert-danger">Please select both book and member.</div>';
    } else {
        if ($issue->issue($book_id, $member_id)) {
            $message = '<div class="alert alert-success">✅ Book issued successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Failed to issue book.</div>';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="card shadow-sm p-4">
    <h2 class="mb-4">📖 Issue Book to Member</h2>

    <?php echo $message; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Select Book</label>
            <select name="book_id" class="form-select" required>
                <option value="">-- Select Book --</option>
                <?php foreach ($books as $b) { ?>
                    <option value="<?php echo $b['id']; ?>">
                        <?php echo htmlspecialchars($b['title']) . " by " . htmlspecialchars($b['author']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Member</label>
            <select name="member_id" class="form-select" required>
                <option value="">-- Select Member --</option>
                <?php foreach ($members as $m) { ?>
                    <option value="<?php echo $m['id']; ?>">
                        <?php echo htmlspecialchars($m['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" name="issue_book" class="btn btn-primary">📚 Issue Book</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
