<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // needed for member view

// Handle Add Reservation (Admin/Librarian only)
if(isset($_POST['add_reservation']) && ($role == 'admin' || $role == 'librarian')){
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];
    $reserved_date = $_POST['reserved_date'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO reservation (member_id, book_id, reserved_date, status) 
                  VALUES ('$member_id','$book_id','$reserved_date','$status')");
}

// Handle Delete Reservation (Admin/Librarian only)
if(isset($_GET['delete']) && ($role == 'admin' || $role == 'librarian')){
    $res_id = $_GET['delete'];
    $conn->query("DELETE FROM reservation WHERE reservation_id='$res_id'");
}

// Handle Edit Reservation (Admin/Librarian only)
if(isset($_POST['edit_reservation']) && ($role == 'admin' || $role == 'librarian')){
    $res_id = $_POST['reservation_id'];
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];
    $reserved_date = $_POST['reserved_date'];
    $status = $_POST['status'];

    $conn->query("UPDATE reservation SET member_id='$member_id', book_id='$book_id', reserved_date='$reserved_date', status='$status' 
                  WHERE reservation_id='$res_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

// Fetch reservations
if($role == 'member'){
    // members can only see their own reservations
    $query = "SELECT r.*, b.title, m.full_name 
              FROM reservation r 
              JOIN books b ON r.book_id=b.book_id 
              JOIN members m ON r.member_id=m.member_id 
              WHERE r.member_id=(SELECT member_id FROM members WHERE user_id='$user_id')
              ORDER BY r.reservation_id DESC";
} else {
    $query = "SELECT r.*, b.title, m.full_name 
              FROM reservation r 
              JOIN books b ON r.book_id=b.book_id 
              JOIN members m ON r.member_id=m.member_id 
              WHERE b.title LIKE '%$search%' OR m.full_name LIKE '%$search%'
              ORDER BY r.reservation_id DESC";
}
$result = $conn->query($query);

// Fetch members and books for dropdowns
$members = $conn->query("SELECT * FROM members");
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Reservations</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        select, input[type=text], input[type=date] { padding:5px; margin:5px 0; width:200px; }
        input[type=submit] { padding:5px 10px; margin-top:10px; }
        .edit-link, .delete-link { margin-right:10px; color:#007BFF; text-decoration:none; }
        .delete-link:hover { color:red; }
    </style>
</head>
<body>
<!-- Dashboard link -->
    <div class="top-nav">
        <a href="dashboard.php">Dashboard</a>
    </div>
    <h2>Manage Reservations</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by book or member" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member'){ ?>
    <!-- Add / Edit Reservation Form -->
    <?php
    if(isset($_GET['edit'])){
        $res_id = $_GET['edit'];
        $res = $conn->query("SELECT * FROM reservation WHERE reservation_id='$res_id'")->fetch_assoc();
    ?>
    <h3>Edit Reservation</h3>
    <form method="POST" action="">
        <input type="hidden" name="reservation_id" value="<?php echo $res['reservation_id']; ?>">
        Member:
        <select name="member_id" required>
            <?php while($m = $members->fetch_assoc()){ ?>
                <option value="<?php echo $m['member_id']; ?>" <?php if($m['member_id']==$res['member_id']) echo 'selected'; ?>><?php echo $m['full_name']; ?></option>
            <?php } ?>
        </select><br>
        Book:
        <select name="book_id" required>
            <?php while($b = $books->fetch_assoc()){ ?>
                <option value="<?php echo $b['book_id']; ?>" <?php if($b['book_id']==$res['book_id']) echo 'selected'; ?>><?php echo $b['title']; ?></option>
            <?php } ?>
        </select><br>
        Reserved Date: <input type="date" name="reserved_date" value="<?php echo $res['reserved_date']; ?>" required><br>
        Status:
        <select name="status" required>
            <option value="pending" <?php if($res['status']=='pending') echo 'selected'; ?>>pending</option>
            <option value="fulfilled" <?php if($res['status']=='fulfilled') echo 'selected'; ?>>fulfilled</option>
            <option value="cancelled" <?php if($res['status']=='cancelled') echo 'selected'; ?>>cancelled</option>
        </select><br>
        <input type="submit" name="edit_reservation" value="Update Reservation">
    </form>
    <?php } else { ?>
    <h3>Add New Reservation</h3>
    <form method="POST" action="">
        Member:
        <select name="member_id" required>
            <?php while($m = $members->fetch_assoc()){ ?>
                <option value="<?php echo $m['member_id']; ?>"><?php echo $m['full_name']; ?></option>
            <?php } ?>
        </select><br>
        Book:
        <select name="book_id" required>
            <?php while($b = $books->fetch_assoc()){ ?>
                <option value="<?php echo $b['book_id']; ?>"><?php echo $b['title']; ?></option>
            <?php } ?>
        </select><br>
        Reserved Date: <input type="date" name="reserved_date" required><br>
        Status:
        <select name="status" required>
            <option value="pending">pending</option>
            <option value="fulfilled">fulfilled</option>
            <option value="cancelled">cancelled</option>
        </select><br>
        <input type="submit" name="add_reservation" value="Add Reservation">
    </form>
    <?php } ?>
    <?php } ?>

    <!-- Reservation List Table -->
    <h3>Reservation List</h3>
    <table>
        <tr>
            <th>ID</th><th>Member</th><th>Book</th><th>Reserved Date</th><th>Status</th>
            <?php if($role != 'member'){ ?><th>Actions</th><?php } ?>
        </tr>
        <?php while($row = $result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['reservation_id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['reserved_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <?php if($role != 'member'){ ?>
            <td>
                <a class="edit-link" href="reservation.php?edit=<?php echo $row['reservation_id']; ?>">Edit</a>
                <a class="delete-link" href="reservation.php?delete=<?php echo $row['reservation_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
