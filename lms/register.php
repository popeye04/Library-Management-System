<?php
// register.php
session_start();
require 'db.php';

$err = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');

    if ($username === '' || $password === '' || $confirm === '') {
        $err = "All fields are required.";
    } elseif ($password !== $confirm) {
        $err = "Passwords do not match.";
    } else {
        // check username exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $err = "Username already exists.";
            $stmt->close();
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'member';
            $ins = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
            $ins->bind_param("sss", $username, $hash, $role);
            if ($ins->execute()) {
                $success = "Account created. You can <a href='login.php'>login now</a>.";
            } else {
                $err = "Database error: " . $conn->error;
            }
            $ins->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register - LMS</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-cover" style="background-image:url('images/login.jpg');">
  <div class="card">
    <h2>Create Account</h2>

    <?php if ($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>

    <form method="post" action="">
      <input type="text" name="username" placeholder="Choose username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Confirm password" required>
      <button type="submit">Register</button>
    </form>

    <a class="small-link" href="login.php">Back to login</a>
  </div>
</body>
</html>
