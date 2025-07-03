<?php
require_once 'classes/Database.php';
require_once 'classes/Member.php';

// Connect DB
$db = new Database();
$conn = $db->connect();
$member = new Member($conn);

$members = $member->getAll();
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">👥 Member List</h2>

<?php if (count($members) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                    <tr>
                        <td><?php echo $m['id']; ?></td>
                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                        <td><?php echo htmlspecialchars($m['phone']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No members found.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
