<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shabab Lost & Found Assistant</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <h2>Welcome to Shabab Lost & Found Assistant</h2>
  <p class="intro">
    Helping UTM students and staff report and find lost items with ease. Use the buttons above to report or search items.
  </p>

<?php
include 'db.php';

// Fetch all lost items (you can limit if needed)
$lostQuery = "SELECT * FROM lost_items ORDER BY date_lost DESC";
$lostResult = $conn->query($lostQuery);

// Fetch all found items
$foundQuery = "SELECT * FROM found_items ORDER BY date_found DESC";
$foundResult = $conn->query($foundQuery);
?>

<div id="recent-reports">

  <?php if ($lostResult && $lostResult->num_rows > 0): ?>
    <?php while ($row = $lostResult->fetch_assoc()): ?>
      <div class="report-card red-box">
        <div class="item-label lost-label">🆘 LOST</div>
        <h3><?= htmlspecialchars($row['item_name']) ?></h3>
        <?php if ($row['image']): ?>
          <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Item Image" style="width:100%; max-height:200px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
        <?php endif; ?>
        <p><strong>Date:</strong> <?= htmlspecialchars($row['date_lost']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
            
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $row['username']): ?>
          <a href="edit-lost.php?id=<?= $row['id'] ?>" class="header-link" style="margin-top:10px; display:inline-block; margin-right:10px;">Edit</a>
          
          <form method="POST" action="delete-report.php" onsubmit="return confirm('Are you sure you want to delete this report?');" class="inline-form">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="hidden" name="type" value="lost">
            <button type="submit" class="delete-button" style="background-color:#ff5722; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:4px;">Delete</button>
        </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <?php if ($foundResult && $foundResult->num_rows > 0): ?>
    <?php while ($row = $foundResult->fetch_assoc()): ?>
      <div class="report-card orange-box">
        <div class="item-label found-label">🔍 FOUND</div>
        <h3><?= htmlspecialchars($row['item_name']) ?></h3>
        <?php if ($row['image']): ?>
          <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Item Image" class="report-image">
        <?php endif; ?>
        <p><strong>Date:</strong> <?= htmlspecialchars($row['date_found']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>

        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $row['username']): ?>
          <a href="edit-found.php?id=<?= $row['id'] ?>" class="header-link" style="margin-top:10px; display:inline-block; margin-right:10px;">Edit</a>

<form method="POST" action="delete-report.php" onsubmit="return confirm('Are you sure you want to delete this report?');" class="inline-form">
  <input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="type" value="found">
  <button type="submit" class="delete-button">Delete</button>
        </form>


        <?php endif; ?>

      </div>
    <?php endwhile; ?>
  <?php endif; ?>

</div>

</main>

<?php include 'footer.php'; ?>

</body>
</html>
