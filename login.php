<?php
include 'db.php';
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed);
    
    if ($stmt->fetch() && password_verify($password, $hashed)) {
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit;
    } else {
        $message = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login | Shabab Lost & Found</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <h2>Login to Your Account</h2>
  <form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>
  </form>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
