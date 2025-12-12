<?php
session_start();
session_destroy();
?>
<html>
<head>
<title>Logout</title>
<style>
body {
    background: url("images/logout.jpg") no-repeat center center fixed;
    background-size: cover;
    font-family: Arial;
}
.box {
    width: 350px;
    background: white;
    padding: 20px;
    margin: 120px auto;
    border-radius: 10px;
    text-align:center;
}
a {
    padding: 8px 14px;
    background: #1976D2;
    color: white;
    text-decoration:none;
    border-radius:6px;
}
</style>
</head>
<body>

<div class="box">
<h2>You have logged out.</h2>
<a href="login.php">Go to Login</a>
</div>

</body>
</html>
