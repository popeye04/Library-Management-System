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

// Handle Add Member
if(isset($_POST['add_member'])) {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $dob = $_POST['date_of_birth'];
    $membership_date = $_POST['membership_date'];
    $status = $_POST['member_status'];
    $type_id = $_POST['type_id'];

    $sql = "INSERT INTO members (user_id, full_name, gender, phone, email, address, date_of_birth, membership_date, member_status, type_id)
            VALUES ('$user_id','$full_name','$gender','$phone','$email','$address','$dob','$membership_date','$status','$type_id')";
    $conn->query($sql);
}

// Handle Delete Member
if(isset($_GET['delete'])) {
    $member_id = $_GET['delete'];
    $conn->query("DELETE FROM members WHERE member_id='$member_id'");
}

// Handle Edit Member
if(isset($_POST['edit_member'])) {
    $member_id = $_POST['member_id'];
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $dob = $_POST['date_of_birth'];
    $membership_date = $_POST['membership_date'];
    $status = $_POST['member_status'];
    $type_id = $_POST['type_id'];

    $conn->query("UPDATE members SET user_id='$user_id', full_name='$full_name', gender='$gender', phone='$phone', email='$email', address='$address', date_of_birth='$dob', membership_date='$membership_date', member_status='$status', type_id='$type_id' WHERE member_id='$member_id'");
}

// Fetch Members for display
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM members WHERE full_name LIKE '%$search%' ORDER BY member_id DESC";
}else{
    $query = "SELECT * FROM members ORDER BY member_id DESC";
}
$result = $conn->query($query);

// Fetch users and membership types for dropdowns
$users = $conn->query("SELECT * FROM users");
$types = $conn->query("SELECT * FROM membership_type");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        select, input[type=text], input[type=date], input[type=email] { padding:5px; margin:5px 0; width:200px; }
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

    <h2>Manage Members</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Add / Edit Form -->
    <?php
    if(isset($_GET['edit'])){
        $member_id = $_GET['edit'];
        $member = $conn->query("SELECT * FROM members WHERE member_id='$member_id'")->fetch_assoc();
    ?>
    <h3>Edit Member</h3>
    <form method="POST" action="">
        <input type="hidden" name="member_id" value="<?php echo $member['member_id']; ?>">
        User:
        <select name="user_id" required>
            <?php while($user = $users->fetch_assoc()){ ?>
                <option value="<?php echo $user['user_id']; ?>" <?php if($user['user_id']==$member['user_id']) echo 'selected'; ?>><?php echo $user['username']; ?></option>
            <?php } ?>
        </select><br>
        Full Name: <input type="text" name="full_name" value="<?php echo $member['full_name']; ?>" required><br>
        Gender: <input type="text" name="gender" value="<?php echo $member['gender']; ?>" required><br>
        Phone: <input type="text" name="phone" value="<?php echo $member['phone']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $member['email']; ?>" required><br>
        Address: <input type="text" name="address" value="<?php echo $member['address']; ?>" required><br>
        Date of Birth: <input type="date" name="date_of_birth" value="<?php echo $member['date_of_birth']; ?>" required><br>
        Membership Date: <input type="date" name="membership_date" value="<?php echo $member['membership_date']; ?>" required><br>
        Status:
        <select name="member_status" required>
            <option value="active" <?php if($member['member_status']=='active') echo 'selected'; ?>>Active</option>
            <option value="expired" <?php if($member['member_status']=='expired') echo 'selected'; ?>>Expired</option>
            <option value="blocked" <?php if($member['member_status']=='blocked') echo 'selected'; ?>>Blocked</option>
        </select><br>
        Membership Type:
        <select name="type_id" required>
            <?php while($type = $types->fetch_assoc()){ ?>
                <option value="<?php echo $type['type_id']; ?>" <?php if($type['type_id']==$member['type_id']) echo 'selected'; ?>><?php echo $type['type_name']; ?></option>
            <?php } ?>
        </select><br>
        <input type="submit" name="edit_member" value="Update Member">
    </form>
    <?php } else { ?>
    <h3>Add New Member</h3>
    <form method="POST" action="">
        User:
        <select name="user_id" required>
            <?php while($user = $users->fetch_assoc()){ ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
            <?php } ?>
        </select><br>
        Full Name: <input type="text" name="full_name" required><br>
        Gender: <input type="text" name="gender" required><br>
        Phone: <input type="text" name="phone" required><br>
        Email: <input type="email" name="email" required><br>
        Address: <input type="text" name="address" required><br>
        Date of Birth: <input type="date" name="date_of_birth" required><br>
        Membership Date: <input type="date" name="membership_date" required><br>
        Status:
        <select name="member_status" required>
            <option value="active">Active</option>
            <option value="expired">Expired</option>
            <option value="blocked">Blocked</option>
        </select><br>
        Membership Type:
        <select name="type_id" required>
            <?php while($type = $types->fetch_assoc()){ ?>
                <option value="<?php echo $type['type_id']; ?>"><?php echo $type['type_name']; ?></option>
            <?php } ?>
        </select><br>
        <input type="submit" name="add_member" value="Add Member">
    </form>
    <?php } ?>

    <!-- Members List Table -->
    <h3>Members List</h3>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Full Name</th><th>Gender</th><th>Phone</th><th>Email</th><th>Address</th><th>DOB</th><th>Membership Date</th><th>Status</th><th>Type</th><th>Actions</th>
        </tr>
        <?php
        $result = $conn->query($query); // re-run for list
        while($row = $result->fetch_assoc()){
            $username = $conn->query("SELECT username FROM users WHERE user_id='".$row['user_id']."'")->fetch_assoc()['username'];
            $type_name = $conn->query("SELECT type_name FROM membership_type WHERE type_id='".$row['type_id']."'")->fetch_assoc()['type_name'];
        ?>
        <tr>
            <td><?php echo $row['member_id']; ?></td>
            <td><?php echo $username; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['date_of_birth']; ?></td>
            <td><?php echo $row['membership_date']; ?></td>
            <td><?php echo $row['member_status']; ?></td>
            <td><?php echo $type_name; ?></td>
            <td>
                <a class="edit-link" href="members.php?edit=<?php echo $row['member_id']; ?>">Edit</a>
                <a class="delete-link" href="members.php?delete=<?php echo $row['member_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
