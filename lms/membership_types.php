<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Add / Edit / Delete only for admin/librarian
if(isset($_POST['add_type']) && ($role == 'admin' || $role == 'librarian')){
    $type_name = $_POST['type_name'];
    $duration_months = $_POST['duration_months'];
    $annual_fee = $_POST['annual_fee'];
    $conn->query("INSERT INTO membership_type (type_name, duration_months, annual_fee) VALUES ('$type_name','$duration_months','$annual_fee')");
}

if(isset($_GET['delete']) && ($role == 'admin' || $role == 'librarian')){
    $type_id = $_GET['delete'];
    $conn->query("DELETE FROM membership_type WHERE type_id='$type_id'");
}

if(isset($_POST['edit_type']) && ($role == 'admin' || $role == 'librarian')){
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    $duration_months = $_POST['duration_months'];
    $annual_fee = $_POST['annual_fee'];
    $conn->query("UPDATE membership_type SET type_name='$type_name', duration_months='$duration_months', annual_fee='$annual_fee' WHERE type_id='$type_id'");
}

// Search
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM membership_type WHERE type_name LIKE '%$search%' ORDER BY type_id DESC";
} else {
    $query = "SELECT * FROM membership_type ORDER BY type_id DESC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Membership Types</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        input[type=text], input[type=number] { padding:5px; margin:5px 0; width:200px; }
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
<h2>Membership Types</h2>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search type" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<?php if($role != 'member'){ ?>
<h3>Add / Edit Membership Type</h3>
<?php
if(isset($_GET['edit'])){
    $type_id = $_GET['edit'];
    $type = $conn->query("SELECT * FROM membership_type WHERE type_id='$type_id'")->fetch_assoc();
?>
<form method="POST" action="">
    <input type="hidden" name="type_id" value="<?php echo $type['type_id']; ?>">
    Type Name: <input type="text" name="type_name" value="<?php echo $type['type_name']; ?>" required><br>
    Duration Months: <input type="number" name="duration_months" value="<?php echo $type['duration_months']; ?>" required><br>
    Annual Fee: <input type="number" step="0.01" name="annual_fee" value="<?php echo $type['annual_fee']; ?>" required><br>
    <input type="submit" name="edit_type" value="Update Type">
</form>
<?php } else { ?>
<form method="POST" action="">
    Type Name: <input type="text" name="type_name" required><br>
    Duration Months: <input type="number" name="duration_months" required><br>
    Annual Fee: <input type="number" step="0.01" name="annual_fee" required><br>
    <input type="submit" name="add_type" value="Add Type">
</form>
<?php } ?>
<?php } ?>

<h3>Membership Types List</h3>
<table>
    <tr><th>ID</th><th>Type Name</th><th>Duration Months</th><th>Annual Fee</th>
    <?php if($role != 'member'){ ?><th>Actions</th><?php } ?></tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['type_id']; ?></td>
        <td><?php echo $row['type_name']; ?></td>
        <td><?php echo $row['duration_months']; ?></td>
        <td><?php echo $row['annual_fee']; ?></td>
        <?php if($role != 'member'){ ?>
        <td>
            <a class="edit-link" href="membership_types.php?edit=<?php echo $row['type_id']; ?>">Edit</a>
            <a class="delete-link" href="membership_types.php?delete=<?php echo $row['type_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</body>
</html>
