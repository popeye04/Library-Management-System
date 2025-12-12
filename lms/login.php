<?php
session_start();
include 'db.php';

$error = '';
$success = '';

// Handle Login
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

// Handle Registration (New Member)
if(isset($_POST['register'])){
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $role = 'member'; // default role for new users

    // Check if username exists
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if($check->num_rows > 0){
        $error = "Username already exists!";
    } else {
        $conn->query("INSERT INTO users (username, password, role, created_at) VALUES ('$username','$password','$role', NOW())");
        $success = "Account created successfully! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Virtual Library</title>
    <style>
        body {
            font-family: Arial;
            margin:0;
            padding:0;
            background: url('images/login.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
    width: 378px; /* increased from 300px */
    background: rgba(255,255,255,0.9);
    padding: 30px; /* increased padding for better spacing */
    margin: 100px auto;
    border-radius: 10px;
}

        h2 { text-align:center; }
        input[type=text], input[type=password] { width:100%; padding:10px; margin:5px 0; }
        input[type=submit] { width:100%; padding:10px; margin-top:10px; cursor:pointer; }
        .message { color:red; text-align:center; }
        .success { color:green; text-align:center; }
        .register-link { text-align:center; margin-top:10px; display:block; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if($error != '') echo "<p class='message'>$error</p>"; ?>
    <?php if($success != '') echo "<p class='success'>$success</p>"; ?>

    <!-- Login Form -->
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>

    <a class="register-link" href="#" onclick="document.getElementById('registerForm').style.display='block'; this.style.display='none';">Create New Account</a>

    <!-- Registration Form -->
    <div id="registerForm" style="display:none;">
        <h3>Register</h3>
        <form method="POST" action="">
            <input type="text" name="reg_username" placeholder="Choose Username" required>
            <input type="password" name="reg_password" placeholder="Choose Password" required>
            <input type="submit" name="register" value="Register">
        </form>
    </div>
</div>
</body>
</html>
