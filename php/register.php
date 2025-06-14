<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Shabab Lost & Found Assistant</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .message-box {
      margin: 20px auto;
      padding: 15px;
      width: 100%;
      border-radius: 8px;
      font-size: 1rem;
      text-align: center;
    }

    .message-box.success {
      background-color: #d4edda;
      color: #155724;
      width: 90%;
      border: 1px solid #c3e6cb;
    }

    .message-box.error {
      background-color: #f8d7da;
      color: #721c24;
    width: 90%;
      border: 1px solid #f5c6cb;
    }

    a {
      color: #007bff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="container">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        echo "<div class='message-box error'>⚠️ Please fill in all fields.</div>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<div class='message-box error'>❌ Username or Email already exists.</div>";
        $stmt->close();
        $conn->close();
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<div class='message-box success'>✅ Registration successful! <a href='../index.html'>Home";
    } else {
        echo "<div class='message-box error'>❌ Error: " . htmlspecialchars($conn->error) . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
</div>
</body>
</html>
