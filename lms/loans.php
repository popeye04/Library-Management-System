<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // Needed for member view

// Handle Add Loan (Admin/Librarian only)
if(isset($_POST['add_loan']) && ($role == 'admin' || $role == 'librarian')){
    $member_id = $_POST['member_id'];
    $copy_id = $_POST['copy_id'];
    $borrowed_date = $_POST['borrowed_date'];
    $due_date = $_POST['due_date'];
    $returned_date = $_POST['returned_date'];

    $conn->query("INSERT INTO loans (member_id, copy_id, borrowed_date, due_date, returned_date)
                  VALUES ('$member_id', '$copy_id', '$borrowed_date', '$due_date', '$returned_date')");
}

// Handle Delete Loan (Admin/Librarian only)
if(isset($_GET['delete']) && ($role == 'admin' || $role == 'librarian')){
    $loan_id = $_GET['delete'];
    $conn->query("DELETE FROM loans WHERE loan_id='$loan_id'");
}

// Handle Edit Loan (Admin/Librarian only)
if(isset($_POST['edit_loan']) && ($role == 'admin' || $role == 'librarian')){
    $loan_id = $_POST['loan_id'];
    $member_id = $_POST['member_id'];
    $copy_id = $_POST['copy_id'];
    $borrowed_date = $_POST['borrowed_date'];
    $due_date = $_POST['due_date'];
    $returned_date = $_POST['returned_date'];

    $conn->query("UPDATE loans SET member_id='$member_id', copy_id='$copy_id', borrowed_date='$borrowed_date', due_date='$due_date', returned_date='$returned_date' 
                  WHERE loan_id='$loan_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

// Fetch loans
if($role == 'member'){
    // members can only see their own loans
    $query = "SELECT l.*, b.title, bc.shelf_location
              FROM loans l
              JOIN book_copies bc ON l.copy_id=bc.copy_id
              JOIN books b ON bc.book_id=b.book_id
              WHERE l.member_id=(SELECT member_id FROM members WHERE user_id='$user_id')
              ORDER BY l.loan_id DESC";
} else {
    $query = "SELECT l.*, b.title, bc.shelf_location, m.full_name
              FROM loans l
              JOIN book_copies bc ON l.copy_id=bc.copy_id
              JOIN books b ON bc.book_id=b.book_id
              JOIN members m ON l.member_id=m.member_id
              WHERE b.title LIKE '%$search%' OR m.full_name LIKE '%$search%'
              ORDER BY l.loan_id DESC";
}

$result = $conn->query($query);

// Fetch members and book copies for dropdowns
$members = $conn->query("SELECT * FROM members");
$copies = $conn->query("SELECT bc.copy_id, b.title FROM book_copies bc JOIN books b ON bc.book_id=b.book_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Loans</title>
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
<!-- Dashboard link -->
    <div class="top-nav">
        <a href="dashboard.php">Dashboard</a>
    </div>
<h2>Manage Loans</h2>

<!-- Search -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by book or member" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<?php if($role != 'member'){ ?>
<!-- Add / Edit Loan Form -->
<?php
if(isset($_GET['edit'])){
    $loan_id = $_GET['edit'];
    $loan = $conn->query("SELECT * FROM loans WHERE loan_id='$loan_id'")->fetch_assoc();
?>
<h3>Edit Loan</h3>
<form method="POST" action="">
    <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
    Member:
    <select name="member_id" required>
        <?php while($m = $members->fetch_assoc()){ ?>
            <option value="<?php echo $m['member_id']; ?>" <?php if($m['member_id']==$loan['member_id']) echo 'selected'; ?>><?php echo $m['full_name']; ?></option>
        <?php } ?>
    </select><br>
    Book Copy:
    <select name="copy_id" required>
        <?php while($c = $copies->fetch_assoc()){ ?>
            <option value="<?php echo $c['copy_id']; ?>" <?php if($c['copy_id']==$loan['copy_id']) echo 'selected'; ?>><?php echo $c['title']; ?> (Copy ID <?php echo $c['copy_id']; ?>)</option>
        <?php } ?>
    </select><br>
    Borrowed Date: <input type="date" name="borrowed_date" value="<?php echo $loan['borrowed_date']; ?>" required><br>
    Due Date: <input type="date" name="due_date" value="<?php echo $loan['due_date']; ?>" required><br>
    Returned Date: <input type="date" name="returned_date" value="<?php echo $loan['returned_date']; ?>"><br>
    <input type="submit" name="edit_loan" value="Update Loan">
</form>
<?php } else { ?>
<h3>Add New Loan</h3>
<form method="POST" action="">
    Member:
    <select name="member_id" required>
        <?php while($m = $members->fetch_assoc()){ ?>
            <option value="<?php echo $m['member_id']; ?>"><?php echo $m['full_name']; ?></option>
        <?php } ?>
    </select><br>
    Book Copy:
    <select name="copy_id" required>
        <?php while($c = $copies->fetch_assoc()){ ?>
            <option value="<?php echo $c['copy_id']; ?>"><?php echo $c['title']; ?> (Copy ID <?php echo $c['copy_id']; ?>)</option>
        <?php } ?>
    </select><br>
    Borrowed Date: <input type="date" name="borrowed_date" required><br>
    Due Date: <input type="date" name="due_date" required><br>
    Returned Date: <input type="date" name="returned_date"><br>
    <input type="submit" name="add_loan" value="Add Loan">
</form>
<?php } ?>
<?php } ?>

<!-- Loan List Table -->
<h3>Loans List</h3>
<table>
    <tr>
        <th>ID</th><th>Member</th><th>Book Title</th><th>Borrowed Date</th><th>Due Date</th><th>Returned Date</th>
        <?php if($role != 'member'){ ?><th>Actions</th><?php } ?>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['loan_id']; ?></td>
        <?php if($role == 'member'){ ?>
            <td><?php echo $row['member_id']; ?></td>
        <?php } else { ?>
            <td><?php echo $row['full_name']; ?></td>
        <?php } ?>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['borrowed_date']; ?></td>
        <td><?php echo $row['due_date']; ?></td>
        <td><?php echo $row['returned_date']; ?></td>
        <?php if($role != 'member'){ ?>
        <td>
            <a class="edit-link" href="loans.php?edit=<?php echo $row['loan_id']; ?>">Edit</a>
            <a class="delete-link" href="loans.php?delete=<?php echo $row['loan_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</body>
</html>
