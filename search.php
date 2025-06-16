<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search - Shabab Lost & Found Assistant</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <h2>Search Lost & Found Items</h2>

  <!-- Search Form -->
  <form method="GET" action="search.php" id="search-form" style="margin-bottom: 20px;">
    <input type="text" name="query" placeholder="Search for items..." required value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">

    <select name="type" required>
      <option value="lost" <?= (isset($_GET['type']) && $_GET['type'] === 'lost') ? 'selected' : '' ?>>Lost</option>
      <option value="found" <?= (isset($_GET['type']) && $_GET['type'] === 'found') ? 'selected' : '' ?>>Found</option>
    </select>

    <select name="status">
      <option value="">All Statuses</option>
      <option value="unresolved" <?= (isset($_GET['status']) && $_GET['status'] === 'unresolved') ? 'selected' : '' ?>>Unresolved</option>
      <option value="resolved" <?= (isset($_GET['status']) && $_GET['status'] === 'resolved') ? 'selected' : '' ?>>Resolved</option>
    </select>

    <button type="submit">Search</button>
  </form>

<?php
$results = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query']) && isset($_GET['type'])) {
    $query = "%" . trim($_GET['query']) . "%";
    $type = $_GET['type'];
    $status = $_GET['status'] ?? '';

    if ($type === 'lost') {
        $sql = "SELECT * FROM lost_items WHERE (item_name LIKE ? OR description LIKE ?)";
    } else {
        $sql = "SELECT * FROM found_items WHERE (item_name LIKE ? OR description LIKE ?)";
    }

    if ($status === 'resolved' || $status === 'unresolved') {
        $sql .= " AND status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $query, $query, $status);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $query, $query);
    }

    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<div id="search-results">
<?php if (isset($_GET['deleted'])): ?>
  <p style="color: green;">Report successfully deleted.</p>
<?php endif; ?>

<?php if (!empty($results) && $results->num_rows > 0): ?>
  <?php while ($row = $results->fetch_assoc()): ?>
    <div class="report-card <?= $type === 'lost' ? 'red-box' : 'orange-box' ?>">
      <div class="item-label <?= $type === 'lost' ? 'lost-label' : 'found-label' ?>">
        <?= $type === 'lost' ? '🆘 LOST' : '🔍 FOUND' ?>
      </div>

      <h3><?= htmlspecialchars($row['item_name']) ?></h3>

      <?php if ($row['image']): ?>
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Item Image" style="width:100%; max-height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
      <?php endif; ?>

      <p><strong>Date:</strong>
        <?= $type === 'lost' ? htmlspecialchars($row['date_lost']) : htmlspecialchars($row['date_found']) ?>
      </p>
      <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
      <p><?= htmlspecialchars($row['description']) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>

      <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $row['username']): ?>
        <a href="<?= $type === 'lost' ? 'edit-lost.php' : 'edit-found.php' ?>?id=<?= $row['id'] ?>" class="header-link" style="margin-top:10px; display:inline-block; margin-right:10px;">Edit</a>

        <form method="POST" action="delete-report.php" onsubmit="return confirm('Are you sure you want to delete this report?');" class="inline-form">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="hidden" name="type" value="<?= $type ?>">
          <input type="hidden" name="status" value="<?= htmlspecialchars($_GET['status'] ?? '') ?>">
          <button type="submit" class="delete-button" style="background-color:#ff5722; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:4px;">Delete</button>
        </form>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "GET"): ?>
  <p>No results found.</p>
<?php endif; ?>
</div>

</main>

<?php include 'footer.php'; ?>
</body>
</html>
