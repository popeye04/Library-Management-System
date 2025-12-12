<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Author (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_author'])) {
    $name = $_POST['name'];
    $nationality = $_POST['nationality'];
    $birth_year = $_POST['birth_year'];
    $death_year = $_POST['death_year'];

    $conn->query("INSERT INTO authors (name, nationality, birth_year, death_year)
                  VALUES ('$name', '$nationality', '$birth_year', '$death_year')");
}

// Handle Delete Author (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $author_id = $_GET['delete'];
    $conn->query("DELETE FROM authors WHERE author_id='$author_id'");
}

// Handle Edit Author (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_author'])) {
    $author_id = $_POST['author_id'];
    $name = $_POST['name'];
    $nationality = $_POST['nationality'];
    $birth_year = $_POST['birth_year'];
    $death_year = $_POST['death_year'];

    $conn->query("UPDATE authors SET name='$name', nationality='$nationality', birth_year='$birth_year', death_year='$death_year'
                  WHERE author_id='$author_id'");
}

// Handle Search (all roles)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM authors WHERE name LIKE '%$search%' ORDER BY author_id DESC";
} else {
    $query = "SELECT * FROM authors ORDER BY author_id DESC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Authors</title>
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
    <h2>Manage Authors</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Author Form -->
        <?php
        if(isset($_GET['edit'])) {
            $author_id = $_GET['edit'];
            $author = $conn->query("SELECT * FROM authors WHERE author_id='$author_id'")->fetch_assoc();
        ?>
        <h3>Edit Author</h3>
        <form method="POST" action="">
            <input type="hidden" name="author_id" value="<?php echo $author['author_id']; ?>">
            Name: <input type="text" name="name" value="<?php echo $author['name']; ?>" required><br>
            Nationality: <input type="text" name="nationality" value="<?php echo $author['nationality']; ?>"><br>
            Birth Year: <input type="number" name="birth_year" value="<?php echo $author['birth_year']; ?>"><br>
            Death Year: <input type="number" name="death_year" value="<?php echo $author['death_year']; ?>"><br>
            <input type="submit" name="edit_author" value="Update Author">
        </form>
        <?php } else { ?>
        <h3>Add New Author</h3>
        <form method="POST" action="">
            Name: <input type="text" name="name" required><br>
            Nationality: <input type="text" name="nationality"><br>
            Birth Year: <input type="number" name="birth_year"><br>
            Death Year: <input type="number" name="death_year"><br>
            <input type="submit" name="add_author" value="Add Author">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Author List Table -->
    <h3>Authors List</h3>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Nationality</th><th>Birth Year</th><th>Death Year</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['author_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['nationality']; ?></td>
            <td><?php echo $row['birth_year']; ?></td>
            <td><?php echo $row['death_year']; ?></td>
            <?php if($role != 'member') { ?>
            <td>
                <a class="edit-link" href="authors.php?edit=<?php echo $row['author_id']; ?>">Edit</a>
                <a class="delete-link" href="authors.php?delete=<?php echo $row['author_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
