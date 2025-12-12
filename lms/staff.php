<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Staff (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_staff'])) {
    $user_id = $_POST['user_id'];
    $staff_name = $_POST['staff_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $hire_date = $_POST['hire_date'];

    $conn->query("INSERT INTO staff (user_id, staff_name, phone, email, position, hire_date)
                  VALUES ('$user_id','$staff_name','$phone','$email','$position','$hire_date')");
}

// Handle Delete Staff
if($role != 'member' && isset($_GET['delete'])) {
    $staff_id = $_GET['delete'];
    $conn->query("DELETE FROM staff WHERE staff_id='$staff_id'");
}

// Handle Edit Staff
if($role != 'member' && isset($_POST['edit_staff'])) {
    $staff_id = $_POST['staff_id'];
    $user_id = $_POST['user_id'];
    $staff_name = $_POST['staff_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $hire_date = $_POST['hire_date'];

    $conn->query("UPDATE staff SET user_id='$user_id', staff_name='$staff_name', phone='$phone', email='$email',
                  position='$position', hire_date='$hire_date' WHERE staff_id='$staff_id'");
}

// Handle Search (all roles)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM staff WHERE staff_name LIKE '%$search%' ORDER BY staff_id DESC";
} else {
    $query = "SELECT * FROM staff ORDER BY staff_id DESC";
}
$result = $conn->query($query);

// Fetch users for dropdown
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Staff</title>
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
    <h2>Manage Staff</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Staff Form -->
        <?php
        if(isset($_GET['edit'])) {
            $staff_id = $_GET['edit'];
            $staff = $conn->query("SELECT * FROM staff WHERE staff_id='$staff_id'")->fetch_assoc();
        ?>
        <h3>Edit Staff</h3>
        <form method="POST" action="">
            <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">
            User:
            <select name="user_id" required>
                <?php while($user = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $user['user_id']; ?>" <?php if($user['user_id']==$staff['user_id']) echo 'selected'; ?>><?php echo $user['username']; ?></option>
                <?php } ?>
            </select><br>
            Name: <input type="text" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required><br>
            Phone: <input type="text" name="phone" value="<?php echo $staff['phone']; ?>"><br>
            Email: <input type="text" name="email" value="<?php echo $staff['email']; ?>"><br>
            Position: <input type="text" name="position" value="<?php echo $staff['position']; ?>"><br>
            Hire Date: <input type="date" name="hire_date" value="<?php echo $staff['hire_date']; ?>"><br>
            <input type="submit" name="edit_staff" value="Update Staff">
        </form>
        <?php } else { ?>
        <h3>Add New Staff</h3>
        <form method="POST" action="">
            User:
            <select name="user_id" required>
                <?php while($user = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
                <?php } ?>
            </select><br>
            Name: <input type="text" name="staff_name" required><br>
            Phone: <input type="text" name="phone"><br>
            Email: <input type="text" name="email"><br>
            Position: <input type="text" name="position"><br>
            Hire Date: <input type="date" name="hire_date"><br>
            <input type="submit" name="add_staff" value="Add Staff">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Staff List Table -->
    <h3>Staff List</h3>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Position</th><th>Hire Date</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['staff_id']; ?></td>
            <td><?php echo $row['staff_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['position']; ?></td>
            <td><?php echo $row['hire_date']; ?></td>
            <?php if($role != 'member') { ?>
            <td>
                <a class="edit-link" href="staff.php?edit=<?php echo $row['staff_id']; ?>">Edit</a>
                <a class="delete-link" href="staff.php?delete=<?php echo $row['staff_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
