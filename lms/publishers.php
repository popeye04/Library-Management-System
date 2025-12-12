<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Publisher (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_publisher'])) {
    $name = $_POST['publisher_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $conn->query("INSERT INTO publishers (publisher_name, address, phone)
                  VALUES ('$name', '$address', '$phone')");
}

// Handle Delete Publisher (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $publisher_id = $_GET['delete'];
    $conn->query("DELETE FROM publishers WHERE publisher_id='$publisher_id'");
}

// Handle Edit Publisher (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_publisher'])) {
    $publisher_id = $_POST['publisher_id'];
    $name = $_POST['publisher_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $conn->query("UPDATE publishers SET publisher_name='$name', address='$address', phone='$phone'
                  WHERE publisher_id='$publisher_id'");
}

// Handle Search (all roles)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM publishers WHERE publisher_name LIKE '%$search%' ORDER BY publisher_id DESC";
} else {
    $query = "SELECT * FROM publishers ORDER BY publisher_id DESC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Publishers</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        input[type=text] { padding:5px; margin:5px 0; width:200px; }
        input[type=submit] { padding:5px 10px; margin-top:10px; }
        .edit-link, .delete-link { margin-right:10px; color:#007BFF; text-decoration:none; }
        .delete-link:hover { color:red; }
    </style>
</head>
<body>
    <h2>Manage Publishers</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Publisher Form -->
        <?php
        if(isset($_GET['edit'])) {
            $publisher_id = $_GET['edit'];
            $pub = $conn->query("SELECT * FROM publishers WHERE publisher_id='$publisher_id'")->fetch_assoc();
        ?>
        <h3>Edit Publisher</h3>
        <form method="POST" action="">
            <input type="hidden" name="publisher_id" value="<?php echo $pub['publisher_id']; ?>">
            Name: <input type="text" name="publisher_name" value="<?php echo $pub['publisher_name']; ?>" required><br>
            Address: <input type="text" name="address" value="<?php echo $pub['address']; ?>"><br>
            Phone: <input type="text" name="phone" value="<?php echo $pub['phone']; ?>"><br>
            <input type="submit" name="edit_publisher" value="Update Publisher">
        </form>
        <?php } else { ?>
        <h3>Add New Publisher</h3>
        <form method="POST" action="">
            Name: <input type="text" name="publisher_name" required><br>
            Address: <input type="text" name="address"><br>
            Phone: <input type="text" name="phone"><br>
            <input type="submit" name="add_publisher" value="Add Publisher">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Publishers List Table -->
    <h3>Publishers List</h3>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Address</th><th>Phone</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['publisher_id']; ?></td>
            <td><?php echo $row['publisher_name']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <?php if($role != 'member') { ?>
            <td>
                <a class="edit-link" href="publishers.php?edit=<?php echo $row['publisher_id']; ?>">Edit</a>
                <a class="delete-link" href="publishers.php?delete=<?php echo $row['publisher_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
