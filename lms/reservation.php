<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Handle Add Reservation (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_reservation'])) {
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];
    $reserved_date = $_POST['reserved_date'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO reservation (member_id, book_id, reserved_date, status)
                  VALUES ('$member_id','$book_id','$reserved_date','$status')");
}

// Handle Delete Reservation
if($role != 'member' && isset($_GET['delete'])) {
    $reservation_id = $_GET['delete'];
    $conn->query("DELETE FROM reservation WHERE reservation_id='$reservation_id'");
}

// Handle Edit Reservation
if($role != 'member' && isset($_POST['edit_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];
    $reserved_date = $_POST['reserved_date'];
    $status = $_POST['status'];

    $conn->query("UPDATE reservation SET member_id='$member_id', book_id='$book_id',
                  reserved_date='$reserved_date', status='$status' WHERE reservation_id='$reservation_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    if($role == 'member') {
        $query = "SELECT r.*, b.title, m.full_name 
                  FROM reservation r
                  JOIN books b ON r.book_id=b.book_id
                  JOIN members m ON r.member_id=m.member_id
                  WHERE r.member_id='$user_id' AND b.title LIKE '%$search%' 
                  ORDER BY r.reservation_id DESC";
    } else {
        $query = "SELECT r.*, b.title, m.full_name 
                  FROM reservation r
                  JOIN books b ON r.book_id=b.book_id
                  JOIN members m ON r.member_id=m.member_id
                  WHERE b.title LIKE '%$search%'
                  ORDER BY r.reservation_id DESC";
    }
} else {
    if($role == 'member') {
        $query = "SELECT r.*, b.title, m.full_name 
                  FROM reservation r
                  JOIN books b ON r.book_id=b.book_id
                  JOIN members m ON r.member_id=m.member_id
                  WHERE r.member_id='$user_id'
                  ORDER BY r.reservation_id DESC";
    } else {
        $query = "SELECT r.*, b.title, m.full_name 
                  FROM reservation r
                  JOIN books b ON r.book_id=b.book_id
                  JOIN members m ON r.member_id=m.member_id
                  ORDER BY r.reservation_id DESC";
    }
}

$result = $conn->query($query);

// Fetch members and books for dropdown (Admin & Librarian)
if($role != 'member') {
    $members = $conn->query("SELECT * FROM members");
    $books = $conn->query("SELECT * FROM books");
}
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
    <h2>Manage Reservations</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by book title" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Reservation Form -->
        <?php
        if(isset($_GET['edit'])) {
            $reservation_id = $_GET['edit'];
            $res = $conn->query("SELECT * FROM reservation WHERE reservation_id='$reservation_id'")->fetch_assoc();
        ?>
        <h3>Edit Reservation</h3>
        <form method="POST" action="">
            <input type="hidden" name="reservation_id" value="<?php echo $res['reservation_id']; ?>">
            Member:
            <select name="member_id" required>
                <?php while($mem = $members->fetch_assoc()) { ?>
                    <option value="<?php echo $mem['member_id']; ?>" <?php if($mem['member_id']==$res['member_id']) echo 'selected'; ?>><?php echo $mem['full_name']; ?></option>
                <?php } ?>
            </select><br>
            Book:
            <select name="book_id" required>
                <?php while($book = $books->fetch_assoc()) { ?>
                    <option value="<?php echo $book['book_id']; ?>" <?php if($book['book_id']==$res['book_id']) echo 'selected'; ?>><?php echo $book['title']; ?></option>
                <?php } ?>
            </select><br>
            Reserved Date: <input type="date" name="reserved_date" value="<?php echo $res['reserved_date']; ?>" required><br>
            Status:
            <select name="status" required>
                <?php
                $statuses = ['pending','fulfilled','cancelled'];
                foreach($statuses as $s) {
                    $selected = ($res['status']==$s)? 'selected':'';
                    echo "<option value='$s' $selected>$s</option>";
                }
                ?>
            </select><br>
            <input type="submit" name="edit_reservation" value="Update Reservation">
        </form>
        <?php } else { ?>
        <h3>Add New Reservation</h3>
        <form method="POST" action="">
            Member:
            <select name="member_id" required>
                <?php while($mem = $members->fetch_assoc()) { ?>
                    <option value="<?php echo $mem['member_id']; ?>"><?php echo $mem['full_name']; ?></option>
                <?php } ?>
            </select><br>
            Book:
            <select name="book_id" required>
                <?php while($book = $books->fetch_assoc()) { ?>
                    <option value="<?php echo $book['book_id']; ?>"><?php echo $book['title']; ?></option>
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
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['reservation_id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['reserved_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <?php if($role != 'member') { ?>
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
