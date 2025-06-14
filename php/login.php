<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Shabab Lost & Found Assistant</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f5;
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
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo "<div class='message-box error'>⚠️ Please enter both email and password.</div>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            echo "<div class='message-box success'>✅ Login successful. Welcome, <strong>" . htmlspecialchars($username) . "</strong>! <br><a href='../index.html'>Go to Home</a></div>";
        } else {
            echo "<div class='message-box error'>❌ Incorrect password. Try again.</div>";
        }
    } else {
        echo "<div class='message-box error'>❌ No user found with that email address.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
</div>
</body>
</html>
