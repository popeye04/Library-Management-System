<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // Needed for member view

// Handle Add Fine (Admin/Librarian only)
if(isset($_POST['add_fine']) && ($role == 'admin' || $role == 'librarian')){
    $loan_id = $_POST['loan_id'];
    $fine_amount = $_POST['fine_amount'];
    $is_paid = $_POST['is_paid'];
    $issued_date = $_POST['issued_date'];
    $paid_date = $_POST['paid_date'];

    $conn->query("INSERT INTO fines (loan_id, fine_amount, is_paid, issued_date, paid_date) 
                  VALUES ('$loan_id', '$fine_amount', '$is_paid', '$issued_date', '$paid_date')");
}

// Handle Delete Fine (Admin/Librarian only)
if(isset($_GET['delete']) && ($role == 'admin' || $role == 'librarian')){
    $fine_id = $_GET['delete'];
    $conn->query("DELETE FROM fines WHERE fine_id='$fine_id'");
}

// Handle Edit Fine (Admin/Librarian only)
if(isset($_POST['edit_fine']) && ($role == 'admin' || $role == 'librarian')){
    $fine_id = $_POST['fine_id'];
    $loan_id = $_POST['loan_id'];
    $fine_amount = $_POST['fine_amount'];
    $is_paid = $_POST['is_paid'];
    $issued_date = $_POST['issued_date'];
    $paid_date = $_POST['paid_date'];

    $conn->query("UPDATE fines SET loan_id='$loan_id', fine_amount='$fine_amount', is_paid='$is_paid', issued_date='$issued_date', paid_date='$paid_date' 
                  WHERE fine_id='$fine_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

// Fetch fines
if($role == 'member'){
    $query = "SELECT f.*, b.title, l.borrowed_date
              FROM fines f
              JOIN loans l ON f.loan_id=l.loan_id
              JOIN book_copies bc ON l.copy_id=bc.copy_id
              JOIN books b ON bc.book_id=b.book_id
              WHERE l.member_id=(SELECT member_id FROM members WHERE user_id='$user_id')
              ORDER BY f.fine_id DESC";
} else {
    $query = "SELECT f.*, l.loan_id, b.title, m.full_name
              FROM fines f
              JOIN loans l ON f.loan_id=l.loan_id
              JOIN book_copies bc ON l.copy_id=bc.copy_id
              JOIN books b ON bc.book_id=b.book_id
              JOIN members m ON l.member_id=m.member_id
              WHERE b.title LIKE '%$search%' OR m.full_name LIKE '%$search%'
              ORDER BY f.fine_id DESC";
}

$result = $conn->query($query);

// Fetch loans for dropdown
$loans = $conn->query("SELECT l.loan_id, b.title, m.full_name
                       FROM loans l
                       JOIN book_copies bc ON l.copy_id=bc.copy_id
                       JOIN books b ON bc.book_id=b.book_id
                       JOIN members m ON l.member_id=m.member_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Fines</title>
    <style>
        body { font-family: Arial; margin:20px; }
        table { border-collapse: collapse; width:100%; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#007BFF; color:white; }
        form { margin-top:20px; }
        select, input[type=text], input[type=number], input[type=date] { padding:5px; margin:5px 0; width:200px; }
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
<h2>Manage Fines</h2>

<!-- Search -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by book or member" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<?php if($role != 'member'){ ?>
<!-- Add / Edit Fine Form -->
<?php
if(isset($_GET['edit'])){
    $fine_id = $_GET['edit'];
    $fine = $conn->query("SELECT * FROM fines WHERE fine_id='$fine_id'")->fetch_assoc();
?>
<h3>Edit Fine</h3>
<form method="POST" action="">
    <input type="hidden" name="fine_id" value="<?php echo $fine['fine_id']; ?>">
    Loan:
    <select name="loan_id" required>
        <?php while($l = $loans->fetch_assoc()){ ?>
            <option value="<?php echo $l['loan_id']; ?>" <?php if($l['loan_id']==$fine['loan_id']) echo 'selected'; ?>>
                <?php echo $l['title'].' ('.$l['full_name'].')'; ?>
            </option>
        <?php } ?>
    </select><br>
    Fine Amount: <input type="number" step="0.01" name="fine_amount" value="<?php echo $fine['fine_amount']; ?>" required><br>
    Is Paid:
    <select name="is_paid" required>
        <option value="yes" <?php if($fine['is_paid']=='yes') echo 'selected'; ?>>Yes</option>
        <option value="no" <?php if($fine['is_paid']=='no') echo 'selected'; ?>>No</option>
    </select><br>
    Issued Date: <input type="date" name="issued_date" value="<?php echo $fine['issued_date']; ?>" required><br>
    Paid Date: <input type="date" name="paid_date" value="<?php echo $fine['paid_date']; ?>"><br>
    <input type="submit" name="edit_fine" value="Update Fine">
</form>
<?php } else { ?>
<h3>Add New Fine</h3>
<form method="POST" action="">
    Loan:
    <select name="loan_id" required>
        <?php while($l = $loans->fetch_assoc()){ ?>
            <option value="<?php echo $l['loan_id']; ?>"><?php echo $l['title'].' ('.$l['full_name'].')'; ?></option>
        <?php } ?>
    </select><br>
    Fine Amount: <input type="number" step="0.01" name="fine_amount" required><br>
    Is Paid:
    <select name="is_paid" required>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select><br>
    Issued Date: <input type="date" name="issued_date" required><br>
    Paid Date: <input type="date" name="paid_date"><br>
    <input type="submit" name="add_fine" value="Add Fine">
</form>
<?php } ?>
<?php } ?>

<!-- Fines List Table -->
<h3>Fines List</h3>
<table>
    <tr>
        <th>ID</th><th>Loan</th><th>Book</th><th>Member</th><th>Amount</th><th>Is Paid</th><th>Issued Date</th><th>Paid Date</th>
        <?php if($role != 'member'){ ?><th>Actions</th><?php } ?>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['fine_id']; ?></td>
        <td><?php echo $row['loan_id']; ?></td>
        <td><?php echo $row['title']; ?></td>
        <?php if($role=='member'){ ?>
            <td><?php echo $row['loan_id']; ?></td>
        <?php } else { ?>
            <td><?php echo $row['full_name']; ?></td>
        <?php } ?>
        <td><?php echo $row['fine_amount']; ?></td>
        <td><?php echo $row['is_paid']; ?></td>
        <td><?php echo $row['issued_date']; ?></td>
        <td><?php echo $row['paid_date']; ?></td>
        <?php if($role != 'member'){ ?>
        <td>
            <a class="edit-link" href="fines.php?edit=<?php echo $row['fine_id']; ?>">Edit</a>
            <a class="delete-link" href="fines.php?delete=<?php echo $row['fine_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</body>
</html>
