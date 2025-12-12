<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: url('images/dashboard.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            background-color: rgba(255,255,255,0.9);
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
        }
        h2 { text-align: center; }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; }
        a { text-decoration: none; padding: 8px 12px; background: #007BFF; color: white; border-radius: 5px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <ul>
        <li><a href="books.php">Books</a></li>
        <li><a href="authors.php">Authors</a></li>
        <li><a href="publishers.php">Publishers</a></li>
        <li><a href="categories.php">Categories</a></li>

        <?php if($role != 'member'){ ?>
            <li><a href="book_copies.php">Book Copies</a></li>
            <li><a href="members.php">Members</a></li>
            <li><a href="staff.php">Staff</a></li>
            <li><a href="membership_types.php">Membership Types</a></li>
            <li><a href="loans.php">Loans</a></li>
            <li><a href="fines.php">Fines</a></li>
            <li><a href="reservation.php">Reservations</a></li>
            <li><a href="users.php">Users</a></li>
        <?php } ?>

        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>
