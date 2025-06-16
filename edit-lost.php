<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item || $item['username'] !== $_SESSION['username']) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $update = $conn->prepare("UPDATE lost_items SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    $update->execute();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Lost Item</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <h2>Edit Lost Report Status</h2>
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
