<?php
session_start();
include 'db.php';

if (!isset($_POST['item_id']) || !isset($_POST['type']) || !isset($_POST['status'])) {
  echo "Missing data.";
  exit;
}

$itemId = intval($_POST['item_id']);
$type = $_POST['type'] === 'found' ? 'found_items' : 'lost_items';
$status = $_POST['status'];

if (!in_array($status, ['Open', 'Resolved'])) {
  echo "Invalid status.";
  exit;
}

$query = "UPDATE $type SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $itemId);
$stmt->execute();

header("Location: index.php");
exit;
?>
