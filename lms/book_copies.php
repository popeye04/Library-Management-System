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

// Handle Add Copy
if(isset($_POST['add_copy'])) {
    $book_id = $_POST['book_id'];
    $shelf_location = $_POST['shelf_location'];
    $status = $_POST['status'];
    $acquire_date = $_POST['acquire_date'];

    $sql = "INSERT INTO book_copies (book_id, shelf_location, status, acquire_date) 
            VALUES ('$book_id', '$shelf_location', '$status', '$acquire_date')";
    $conn->query($sql);
}

// Handle Delete Copy
if(isset($_GET['delete'])) {
    $copy_id = $_GET['delete'];
    $conn->query("DELETE FROM book_copies WHERE copy_id='$copy_id'");
}

// Handle Edit Copy
if(isset($_POST['edit_copy'])) {
    $copy_id = $_POST['copy_id'];
    $book_id = $_POST['book_id'];
    $shelf_location = $_POST['shelf_location'];
    $status = $_POST['status'];
    $acquire_date = $_POST['acquire_date'];

    $conn->query("UPDATE book_copies SET book_id='$book_id', shelf_location='$shelf_location', status='$status', acquire_date='$acquire_date' WHERE copy_id='$copy_id'");
}

// Handle Search (by book_id)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM book_copies WHERE book_id LIKE '%$search%' ORDER BY copy_id DESC";
} else {
    $query = "SELECT * FROM book_copies ORDER BY copy_id DESC";
}
$result = $conn->query($query);

// Fetch books for dropdown
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Book Copies</title>
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
    <h2>Manage Book Copies</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="number" name="search" placeholder="Search by Book ID" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Add / Edit Copy Form -->
    <?php
    if(isset($_GET['edit'])) {
        $copy_id = $_GET['edit'];
        $copy = $conn->query("SELECT * FROM book_copies WHERE copy_id='$copy_id'")->fetch_assoc();
    ?>
    <h3>Edit Copy</h3>
    <form method="POST" action="">
        <input type="hidden" name="copy_id" value="<?php echo $copy['copy_id']; ?>">
        Book:
        <select name="book_id" required>
            <?php while($book = $books->fetch_assoc()) { ?>
                <option value="<?php echo $book['book_id']; ?>" <?php if($book['book_id']==$copy['book_id']) echo 'selected'; ?>><?php echo $book['title']; ?></option>
            <?php } ?>
        </select><br>
        Shelf Location: <input type="text" name="shelf_location" value="<?php echo $copy['shelf_location']; ?>" required><br>
        Status:
        <select name="status" required>
            <?php
            $statuses = ['available','borrowed','reserved','lost','damaged'];
            foreach($statuses as $s) {
                $selected = ($copy['status']==$s)? 'selected':'';
                echo "<option value='$s' $selected>$s</option>";
            }
            ?>
        </select><br>
        Acquire Date: <input type="date" name="acquire_date" value="<?php echo $copy['acquire_date']; ?>" required><br>
        <input type="submit" name="edit_copy" value="Update Copy">
    </form>
    <?php } else { ?>
    <h3>Add New Copy</h3>
    <form method="POST" action="">
        Book:
        <select name="book_id" required>
            <?php while($book = $books->fetch_assoc()) { ?>
                <option value="<?php echo $book['book_id']; ?>"><?php echo $book['title']; ?></option>
            <?php } ?>
        </select><br>
        Shelf Location: <input type="text" name="shelf_location" required><br>
        Status:
        <select name="status" required>
            <option value="available">available</option>
            <option value="borrowed">borrowed</option>
            <option value="reserved">reserved</option>
            <option value="lost">lost</option>
            <option value="damaged">damaged</option>
        </select><br>
        Acquire Date: <input type="date" name="acquire_date" required><br>
        <input type="submit" name="add_copy" value="Add Copy">
    </form>
    <?php } ?>

    <!-- Copies List Table -->
    <h3>Book Copies List</h3>
    <table>
        <tr>
            <th>ID</th><th>Book Title</th><th>Shelf Location</th><th>Status</th><th>Acquire Date</th><th>Actions</th>
        </tr>
        <?php
        $result = $conn->query($query); // re-run to fetch after add/edit
        while($row = $result->fetch_assoc()) {
            $book_title = $conn->query("SELECT title FROM books WHERE book_id='".$row['book_id']."'")->fetch_assoc()['title'];
        ?>
        <tr>
            <td><?php echo $row['copy_id']; ?></td>
            <td><?php echo $book_title; ?></td>
            <td><?php echo $row['shelf_location']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['acquire_date']; ?></td>
            <td>
                <a class="edit-link" href="book_copies.php?edit=<?php echo $row['copy_id']; ?>">Edit</a>
                <a class="delete-link" href="book_copies.php?delete=<?php echo $row['copy_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
