<?php
// Load classes
require_once 'classes/Database.php';
require_once 'classes/Book.php';

// Connect to DB
$db = new Database();
$conn = $db->connect();
$book = new Book($conn);

$message = "";

// Handle form submit
if (isset($_POST['add_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $copies = intval($_POST['copies']);

    if ($title === "" || $author === "" || $isbn === "" || $copies <= 0) {
        $message = '<div class="alert alert-danger">All fields are required & copies must be > 0.</div>';
    } else {
        if ($book->add($title, $author, $isbn, $copies)) {
            $message = '<div class="alert alert-success">✅ Book added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Failed to add book. Maybe ISBN already exists?</div>';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="card shadow-sm p-4">
    <h2 class="mb-4">📚 Add New Book</h2>

    <?php echo $message; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Book Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter book title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" name="author" class="form-control" placeholder="Enter author name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" placeholder="Enter ISBN number" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Copies</label>
            <input type="number" name="copies" class="form-control" placeholder="Number of copies" min="1" required>
        </div>

        <button type="submit" name="add_book" class="btn btn-primary">➕ Add Book</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
