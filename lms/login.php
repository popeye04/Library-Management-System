<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login!";
    }
}
?>
<html>
<head>
<title>Login</title>
<style>
body {
    background: url("images/login.jpg") no-repeat center center fixed;
    background-size: cover;
    font-family: Arial;
}

.login-box {
    width: 300px;
    background: white;
    padding: 20px;
    margin: 120px auto;
    border-radius: 8px;
    text-align: center;
}
input {
    width: 90%;
    padding: 8px;
    margin-top: 10px;
}
button {
    width: 95%;
    padding: 8px;
    margin-top: 10px;
}
</style>
</head>
<body>

<div class="login-box">
<h2>Login</h2>

<form method="POST">
<input type="text" name="username" placeholder="Username" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<button type="submit">Login</button>
</form>

<p style="color:red;"><?php echo $error; ?></p>

</div>

</body>
</html>
