<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Membership Type (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_type'])) {
    $type_name = $_POST['type_name'];
    $duration_months = $_POST['duration_months'];
    $annual_fee = $_POST['annual_fee'];

    $conn->query("INSERT INTO membership_type (type_name, duration_months, annual_fee) 
                  VALUES ('$type_name', '$duration_months', '$annual_fee')");
}

// Handle Delete Membership Type (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $type_id = $_GET['delete'];
    $conn->query("DELETE FROM membership_type WHERE type_id='$type_id'");
}

// Handle Edit Membership Type (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_type'])) {
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    $duration_months = $_POST['duration_months'];
    $annual_fee = $_POST['annual_fee'];

    $conn->query("UPDATE membership_type 
                  SET type_name='$type_name', duration_months='$duration_months', annual_fee='$annual_fee' 
                  WHERE type_id='$type_id'");
}

// Fetch all membership types
$result = $conn->query("SELECT * FROM membership_type ORDER BY type_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Membership Types</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        input[type=text], input[type=number] { padding:5px; margin:5px 0; width:200px; }
        input[type=submit] { padding:5px 10px; margin-top:10px; }
        .edit-link, .delete-link { margin-right:10px; color:#007BFF; text-decoration:none; }
        .delete-link:hover { color:red; }
    </style>
</head>
<body>
    <h2>Manage Membership Types</h2>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Form -->
        <?php if(isset($_GET['edit'])) {
            $type_id = $_GET['edit'];
            $type = $conn->query("SELECT * FROM membership_type WHERE type_id='$type_id'")->fetch_assoc();
        ?>
        <h3>Edit Membership Type</h3>
        <form method="POST" action="">
            <input type="hidden" name="type_id" value="<?php echo $type['type_id']; ?>">
            Type Name: <input type="text" name="type_name" value="<?php echo $type['type_name']; ?>" required><br>
            Duration (Months): <input type="number" name="duration_months" value="<?php echo $type['duration_months']; ?>" required><br>
            Annual Fee: <input type="number" step="0.01" name="annual_fee" value="<?php echo $type['annual_fee']; ?>" required><br>
            <input type="submit" name="edit_type" value="Update Type">
        </form>
        <?php } else { ?>
        <h3>Add New Membership Type</h3>
        <form method="POST" action="">
            Type Name: <input type="text" name="type_name" required><br>
            Duration (Months): <input type="number" name="duration_months" required><br>
            Annual Fee: <input type="number" step="0.01" name="annual_fee" required><br>
            <input type="submit" name="add_type" value="Add Type">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- List Table -->
    <h3>Membership Types List</h3>
    <table>
        <tr>
            <th>ID</th><th>Type Name</th><th>Duration (Months)</th><th>Annual Fee</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['type_id']; ?></td>
            <td><?php echo $row['type_name']; ?></td>
            <td><?php echo $row['duration_months']; ?></td>
            <td><?php echo $row['annual_fee']; ?></td>
            <?php if($role != 'member') { ?>
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
