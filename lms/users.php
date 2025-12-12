<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add User (Admin/Librarian only)
if(isset($_POST['add_user']) && ($role == 'admin' || $role == 'librarian')){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_role = $_POST['role'];

    $conn->query("INSERT INTO users (username, password, role, created_at) 
                  VALUES ('$username', '$password', '$user_role', NOW())");
}

// Handle Delete User
if(isset($_GET['delete']) && ($role == 'admin' || $role == 'librarian')){
    $user_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE user_id='$user_id'");
}

// Handle Edit User
if(isset($_POST['edit_user']) && ($role == 'admin' || $role == 'librarian')){
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_role = $_POST['role'];

    $conn->query("UPDATE users SET username='$username', password='$password', role='$user_role' WHERE user_id='$user_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM users WHERE username LIKE '%$search%' ORDER BY user_id DESC";
} else {
    $query = "SELECT * FROM users ORDER BY user_id DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        input[type=text], input[type=password], select { padding:5px; margin:5px 0; width:200px; }
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

<h2>Manage Users</h2>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<?php if($role != 'member'){ ?>
<h3>Add / Edit User</h3>
<?php
if(isset($_GET['edit'])){
    $user_id = $_GET['edit'];
    $user = $conn->query("SELECT * FROM users WHERE user_id='$user_id'")->fetch_assoc();
?>
<form method="POST" action="">
    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
    Username: <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
    Password: <input type="password" name="password" value="<?php echo $user['password']; ?>" required><br>
    Role:
    <select name="role" required>
        <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
        <option value="librarian" <?php if($user['role']=='librarian') echo 'selected'; ?>>Librarian</option>
        <option value="member" <?php if($user['role']=='member') echo 'selected'; ?>>Member</option>
    </select><br>
    <input type="submit" name="edit_user" value="Update User">
</form>
<?php } else { ?>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Role:
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="librarian">Librarian</option>
        <option value="member">Member</option>
    </select><br>
    <input type="submit" name="add_user" value="Add User">
</form>
<?php } ?>
<?php } ?>

<h3>User List</h3>
<table>
    <tr>
        <th>ID</th><th>Username</th><th>Role</th><th>Created At</th>
        <?php if($role != 'member'){ ?><th>Actions</th><?php } ?>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['user_id']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['role']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <?php if($role != 'member'){ ?>
        <td>
            <a class="edit-link" href="users.php?edit=<?php echo $row['user_id']; ?>">Edit</a>
            <a class="delete-link" href="users.php?delete=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</body>
</html>
