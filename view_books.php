<?php
require_once 'classes/Database.php';
require_once 'classes/Book.php';

// Connect DB
$db = new Database();
$conn = $db->connect();
$book = new Book($conn);

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$limit = 5; // Books per page
$offset = ($page - 1) * $limit;

// Fetch books
$books = $book->getAll($limit, $offset, $search);
$total_books = $book->count($search);
$total_pages = ceil($total_books / $limit);
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>📚 Book List</h2>
    <form class="d-flex" method="GET" action="view_books.php">
        <input type="text" name="search" class="form-control me-2" placeholder="Search title or author..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary" type="submit">🔍 Search</button>
    </form>
</div>

<?php if (count($books) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Copies</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                    <tr>
                        <td><?php echo $b['id']; ?></td>
                        <td><?php echo htmlspecialchars($b['title']); ?></td>
                        <td><?php echo htmlspecialchars($b['author']); ?></td>
                        <td><?php echo htmlspecialchars($b['isbn']); ?></td>
                        <td><?php echo $b['copies']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>">« Prev</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>">Next »</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php else: ?>
    <div class="alert alert-info">No books found. Try a different search.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
