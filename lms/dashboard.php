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

        h2 { 
            text-align: center;
            margin-bottom: 30px;
        }

        /* Horizontal grid layout */
        .dashboard-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 0;
        }

        /* Dashboard item boxes */
        .dash-item {
            width: 180px;
            height: 130px;
            background: #007BFF;
            color: white;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            font-size: 18px;
            transition: 0.2s;
        }

        .dash-item:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .icon {
            font-size: 35px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

    <div class="dashboard-grid">

        <a class="dash-item" href="books.php">
            <div class="icon">📘</div>
            Books
        </a>

        <a class="dash-item" href="authors.php">
            <div class="icon">✍️</div>
            Authors
        </a>

        <a class="dash-item" href="publishers.php">
            <div class="icon">🏢</div>
            Publishers
        </a>

        <a class="dash-item" href="categories.php">
            <div class="icon">🏷️</div>
            Categories
        </a>

        <?php if($role != 'member'){ ?>

        <a class="dash-item" href="book_copies.php">
            <div class="icon">📚</div>
            Book Copies
        </a>

        <a class="dash-item" href="members.php">
            <div class="icon">👥</div>
            Members
        </a>

        <a class="dash-item" href="staff.php">
            <div class="icon">🧑‍💼</div>
            Staff
        </a>

        <a class="dash-item" href="membership_types.php">
            <div class="icon">💳</div>
            Membership Types
        </a>

        <a class="dash-item" href="loans.php">
            <div class="icon">🔄</div>
            Loans
        </a>

        <a class="dash-item" href="fines.php">
            <div class="icon">💰</div>
            Fines
        </a>

        <a class="dash-item" href="reservation.php">
            <div class="icon">📅</div>
            Reservations
        </a>

        <a class="dash-item" href="users.php">
            <div class="icon">🛠️</div>
            Users
        </a>

        <?php } ?>

        <a class="dash-item" href="logout.php">
            <div class="icon">🚪</div>
            Logout
        </a>

    </div>
</div>

</body>
</html>
