<?php
// Load classes
require_once 'classes/Database.php';
require_once 'classes/Member.php';

// Connect DB
$db = new Database();
$conn = $db->connect();
$member = new Member($conn);

$message = "";

// Handle form submit
if (isset($_POST['add_member'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name === "" || $email === "" || $phone === "") {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">Invalid email format.</div>';
    } else {
        if ($member->add($name, $email, $phone)) {
            $message = '<div class="alert alert-success">✅ Member added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">❌ Failed to add member. Maybe email already exists?</div>';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="card shadow-sm p-4">
    <h2 class="mb-4">👤 Add New Member</h2>

    <?php echo $message; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Member Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter valid email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
        </div>

        <button type="submit" name="add_member" class="btn btn-primary">➕ Add Member</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
