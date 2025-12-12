<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Members cannot access this page
if($role == 'member'){
    echo "<h2>Access Denied</h2>";
    echo "<p>You do not have permission to access this page.</p>";
    exit();
}

// Handle Add Staff
if(isset($_POST['add_staff'])) {
    $user_id = $_POST['user_id'];
    $staff_name = $_POST['staff_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $hire_date = $_POST['hire_date'];

    $sql = "INSERT INTO staff (user_id, staff_name, phone, email, position, hire_date)
            VALUES ('$user_id','$staff_name','$phone','$email','$position','$hire_date')";
    $conn->query($sql);
}

// Handle Delete Staff
if(isset($_GET['delete'])) {
    $staff_id = $_GET['delete'];
    $conn->query("DELETE FROM staff WHERE staff_id='$staff_id'");
}

// Handle Edit Staff
if(isset($_POST['edit_staff'])) {
    $staff_id = $_POST['staff_id'];
    $user_id = $_POST['user_id'];
    $staff_name = $_POST['staff_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $hire_date = $_POST['hire_date'];

    $conn->query("UPDATE staff SET user_id='$user_id', staff_name='$staff_name', phone='$phone', email='$email', position='$position', hire_date='$hire_date' WHERE staff_id='$staff_id'");
}

// Fetch Staff for display
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM staff WHERE staff_name LIKE '%$search%' ORDER BY staff_id DESC";
}else{
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
        select, input[type=text], input[type=email], input[type=date] { padding:5px; margin:5px 0; width:200px; }
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

    <h2>Manage Staff</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by staff name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Add / Edit Staff Form -->
    <?php
    if(isset($_GET['edit'])){
        $staff_id = $_GET['edit'];
        $staff = $conn->query("SELECT * FROM staff WHERE staff_id='$staff_id'")->fetch_assoc();
    ?>
    <h3>Edit Staff</h3>
    <form method="POST" action="">
        <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">
        User:
        <select name="user_id" required>
            <?php while($user = $users->fetch_assoc()){ ?>
                <option value="<?php echo $user['user_id']; ?>" <?php if($user['user_id']==$staff['user_id']) echo 'selected'; ?>><?php echo $user['username']; ?></option>
            <?php } ?>
        </select><br>
        Staff Name: <input type="text" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required><br>
        Phone: <input type="text" name="phone" value="<?php echo $staff['phone']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $staff['email']; ?>" required><br>
        Position: <input type="text" name="position" value="<?php echo $staff['position']; ?>" required><br>
        Hire Date: <input type="date" name="hire_date" value="<?php echo $staff['hire_date']; ?>" required><br>
        <input type="submit" name="edit_staff" value="Update Staff">
    </form>
    <?php } else { ?>
    <h3>Add New Staff</h3>
    <form method="POST" action="">
        User:
        <select name="user_id" required>
            <?php while($user = $users->fetch_assoc()){ ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
            <?php } ?>
        </select><br>
        Staff Name: <input type="text" name="staff_name" required><br>
        Phone: <input type="text" name="phone" required><br>
        Email: <input type="email" name="email" required><br>
        Position: <input type="text" name="position" required><br>
        Hire Date: <input type="date" name="hire_date" required><br>
        <input type="submit" name="add_staff" value="Add Staff">
    </form>
    <?php } ?>

    <!-- Staff List Table -->
    <h3>Staff List</h3>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Name</th><th>Phone</th><th>Email</th><th>Position</th><th>Hire Date</th><th>Actions</th>
        </tr>
        <?php
        $result = $conn->query($query); // re-run for list
        while($row = $result->fetch_assoc()){
            $username = $conn->query("SELECT username FROM users WHERE user_id='".$row['user_id']."'")->fetch_assoc()['username'];
        ?>
        <tr>
            <td><?php echo $row['staff_id']; ?></td>
            <td><?php echo $username; ?></td>
            <td><?php echo $row['staff_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['position']; ?></td>
            <td><?php echo $row['hire_date']; ?></td>
            <td>
                <a class="edit-link" href="staff.php?edit=<?php echo $row['staff_id']; ?>">Edit</a>
                <a class="delete-link" href="staff.php?delete=<?php echo $row['staff_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
