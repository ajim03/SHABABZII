<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['id']) && !empty($_POST['type'])) {
        $id = intval($_POST['id']);
        $type = trim($_POST['type']); // Trim to remove hidden characters or whitespace

        if ($type === 'lost') {
            $stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
        } elseif ($type === 'found') {
            $stmt = $conn->prepare("DELETE FROM found_items WHERE id = ?");
        } else {
            echo "Invalid type value: " . htmlspecialchars($type);
            exit;
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
header("Location: search.php?deleted=1&type=$type&status=" . urlencode($_POST['status'] ?? ''));
            exit;
        } else {
            echo "Error deleting the record: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Missing or empty form data.";
        echo "<br><pre>";
        print_r($_POST);
        echo "</pre>";
    }

} else {
    echo "Invalid request method.";
}
?>
