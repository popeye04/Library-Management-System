<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Add Book (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $publisher_id = $_POST['publisher_id'];
    $year = $_POST['publication_year'];
    $copies_total = $_POST['copies_total'];
    $copies_available = $_POST['copies_available'];

    $conn->query("INSERT INTO books (title, category_id, publisher_id, publication_year, copies_total, copies_available)
                  VALUES ('$title', '$category_id', '$publisher_id', '$year', '$copies_total', '$copies_available')");
}

// Handle Delete Book (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE book_id='$book_id'");
}

// Handle Edit Book (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_book'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $publisher_id = $_POST['publisher_id'];
    $year = $_POST['publication_year'];
    $copies_total = $_POST['copies_total'];
    $copies_available = $_POST['copies_available'];

    $conn->query("UPDATE books SET title='$title', category_id='$category_id', publisher_id='$publisher_id', 
                  publication_year='$year', copies_total='$copies_total', copies_available='$copies_available' 
                  WHERE book_id='$book_id'");
}

// Handle Search (all roles)
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM books WHERE title LIKE '%$search%' ORDER BY book_id DESC";
} else {
    $query = "SELECT * FROM books ORDER BY book_id DESC";
}
$result = $conn->query($query);

// Fetch Publishers and Categories for dropdowns (Admin & Librarian only)
$publishers = $conn->query("SELECT * FROM publishers");
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        select, input[type=text], input[type=number] { padding:5px; margin:5px 0; width:200px; }
        input[type=submit] { padding:5px 10px; margin-top:10px; }
        .edit-link, .delete-link { margin-right:10px; color:#007BFF; text-decoration:none; }
        .delete-link:hover { color:red; }
    </style>
</head>
<body>
    <h2>Manage Books</h2>

    <!-- Search (all roles) -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by title" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <?php if($role != 'member') { ?>
        <!-- Add / Edit Book Form -->
        <?php
        if(isset($_GET['edit'])) {
            $book_id = $_GET['edit'];
            $book = $conn->query("SELECT * FROM books WHERE book_id='$book_id'")->fetch_assoc();
        ?>
        <h3>Edit Book</h3>
        <form method="POST" action="">
            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
            Title: <input type="text" name="title" value="<?php echo $book['title']; ?>" required><br>
            Category:
            <select name="category_id" required>
                <?php while($cat = $categories->fetch_assoc()) { ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php if($cat['category_id']==$book['category_id']) echo 'selected'; ?>>
                        <?php echo $cat['category_name']; ?>
                    </option>
                <?php } ?>
            </select><br>
            Publisher:
            <select name="publisher_id" required>
                <?php while($pub = $publishers->fetch_assoc()) { ?>
                    <option value="<?php echo $pub['publisher_id']; ?>" <?php if($pub['publisher_id']==$book['publisher_id']) echo 'selected'; ?>>
                        <?php echo $pub['publisher_name']; ?>
                    </option>
                <?php } ?>
            </select><br>
            Publication Year: <input type="number" name="publication_year" value="<?php echo $book['publication_year']; ?>" required><br>
            Copies Total: <input type="number" name="copies_total" value="<?php echo $book['copies_total']; ?>" required><br>
            Copies Available: <input type="number" name="copies_available" value="<?php echo $book['copies_available']; ?>" required><br>
            <input type="submit" name="edit_book" value="Update Book">
        </form>
        <?php } else { ?>
        <h3>Add New Book</h3>
        <form method="POST" action="">
            Title: <input type="text" name="title" required><br>
            Category:
            <select name="category_id" required>
                <?php while($cat = $categories->fetch_assoc()) { ?>
                    <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                <?php } ?>
            </select><br>
            Publisher:
            <select name="publisher_id" required>
                <?php while($pub = $publishers->fetch_assoc()) { ?>
                    <option value="<?php echo $pub['publisher_id']; ?>"><?php echo $pub['publisher_name']; ?></option>
                <?php } ?>
            </select><br>
            Publication Year: <input type="number" name="publication_year" required><br>
            Copies Total: <input type="number" name="copies_total" required><br>
            Copies Available: <input type="number" name="copies_available" required><br>
            <input type="submit" name="add_book" value="Add Book">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Book List Table -->
    <h3>Book List</h3>
    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Category</th><th>Publisher</th><th>Year</th><th>Copies Total</th><th>Copies Available</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php while($row = $result->fetch_assoc()) {
            $pub_name = $conn->query("SELECT publisher_name FROM publishers WHERE publisher_id='".$row['publisher_id']."'")->fetch_assoc()['publisher_name'];
            $cat_name = $conn->query("SELECT category_name FROM categories WHERE category_id='".$row['category_id']."'")->fetch_assoc()['category_name'];
        ?>
        <tr>
            <td><?php echo $row['book_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $cat_name; ?></td>
            <td><?php echo $pub_name; ?></td>
            <td><?php echo $row['publication_year']; ?></td>
            <td><?php echo $row['copies_total']; ?></td>
            <td><?php echo $row['copies_available']; ?></td>
            <?php if($role != 'member') { ?>
            <td>
                <a class="edit-link" href="books.php?edit=<?php echo $row['book_id']; ?>">Edit</a>
                <a class="delete-link" href="books.php?delete=<?php echo $row['book_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
