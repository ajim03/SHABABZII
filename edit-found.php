<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the item ID from the URL
$id = $_GET['id'];

// Fetch the found item from the database
$stmt = $conn->prepare("SELECT * FROM found_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

// Check if the item exists and was submitted by the current user
if (!$item || $item['username'] !== $_SESSION['username']) {
    echo "Unauthorized access.";
    exit;
}

// If form submitted, update the status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $update = $conn->prepare("UPDATE found_items SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    $update->execute();
    header("Location: index.php"); // redirect to homepage after update
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Found Item</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <h2>Edit Found Report Status</h2>
  <form method="POST">
    <label for="status">Status:</label>
    <select name="status">
      <option value="open" <?= $item['status'] == 'open' ? 'selected' : '' ?>>Open</option>
      <option value="resolved" <?= $item['status'] == 'resolved' ? 'selected' : '' ?>>Resolved</option>
    </select>
    <button type="submit">Update</button>
  </form>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
