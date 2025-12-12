<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Category (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_category'])) {
    $name = $_POST['category_name'];
    $conn->query("INSERT INTO categories (category_name) VALUES ('$name')");
}

// Handle Delete Category (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE category_id='$category_id'");
}

// Handle Edit Category (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['category_name'];
    $conn->query("UPDATE categories SET category_name='$name' WHERE category_id='$category_id'");
}

// Handle Search (all roles)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM categories WHERE category_name LIKE '%$search%' ORDER BY category_id DESC";
} else {
    $query = "SELECT * FROM categories ORDER BY category_id DESC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
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
<!-- Dashboard link -->
    <div class="top-nav">
        <a href="dashboard.php">Dashboard</a>
    </div>

    <h2>Manage Categories</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Category Form -->
        <?php
        if(isset($_GET['edit'])) {
            $category_id = $_GET['edit'];
            $cat = $conn->query("SELECT * FROM categories WHERE category_id='$category_id'")->fetch_assoc();
        ?>
        <h3>Edit Category</h3>
        <form method="POST" action="">
            <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
            Name: <input type="text" name="category_name" value="<?php echo $cat['category_name']; ?>" required><br>
            <input type="submit" name="edit_category" value="Update Category">
        </form>
        <?php } else { ?>
        <h3>Add New Category</h3>
        <form method="POST" action="">
            Name: <input type="text" name="category_name" required><br>
            <input type="submit" name="add_category" value="Add Category">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Categories List Table -->
    <h3>Categories List</h3>
    <table>
        <tr>
            <th>ID</th><th>Name</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['category_id']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <?php if($role != 'member') { ?>
            <td>
                <a class="edit-link" href="categories.php?edit=<?php echo $row['category_id']; ?>">Edit</a>
                <a class="delete-link" href="categories.php?delete=<?php echo $row['category_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
