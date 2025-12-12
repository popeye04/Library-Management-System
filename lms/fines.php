<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Handle Add Fine (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_fine'])) {
    $loan_id = $_POST['loan_id'];
    $fine_amount = $_POST['fine_amount'];
    $is_paid = $_POST['is_paid'];
    $issued_date = $_POST['issued_date'];
    $paid_date = $_POST['paid_date'] ? $_POST['paid_date'] : NULL;

    $sql = "INSERT INTO fines (loan_id, fine_amount, is_paid, issued_date, paid_date)
            VALUES ('$loan_id', '$fine_amount', '$is_paid', '$issued_date', '$paid_date')";
    $conn->query($sql);
}

// Handle Delete Fine (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $fine_id = $_GET['delete'];
    $conn->query("DELETE FROM fines WHERE fine_id='$fine_id'");
}

// Handle Edit Fine (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_fine'])) {
    $fine_id = $_POST['fine_id'];
    $loan_id = $_POST['loan_id'];
    $fine_amount = $_POST['fine_amount'];
    $is_paid = $_POST['is_paid'];
    $issued_date = $_POST['issued_date'];
    $paid_date = $_POST['paid_date'] ? $_POST['paid_date'] : NULL;

    $conn->query("UPDATE fines SET loan_id='$loan_id', fine_amount='$fine_amount', is_paid='$is_paid', issued_date='$issued_date', paid_date='$paid_date' WHERE fine_id='$fine_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    if($role == 'member') {
        $query = "SELECT f.* FROM fines f 
                  JOIN loans l ON f.loan_id = l.loan_id 
                  WHERE l.member_id='$user_id' AND f.loan_id LIKE '%$search%' ORDER BY f.fine_id DESC";
    } else {
        $query = "SELECT * FROM fines WHERE loan_id LIKE '%$search%' ORDER BY fine_id DESC";
    }
} else {
    if($role == 'member') {
        $query = "SELECT f.* FROM fines f 
                  JOIN loans l ON f.loan_id = l.loan_id 
                  WHERE l.member_id='$user_id' ORDER BY f.fine_id DESC";
    } else {
        $query = "SELECT * FROM fines ORDER BY fine_id DESC";
    }
}

$result = $conn->query($query);

// Fetch loans for dropdown (Admin & Librarian only)
if($role != 'member') {
    $loans = $conn->query("SELECT loan_id FROM loans");
}
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
    <h2>Manage Fines</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="number" name="search" placeholder="Search by Loan ID" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Add / Edit Fine Form (Admin & Librarian only) -->
    <?php if($role != 'member') { ?>
        <?php
        if(isset($_GET['edit'])) {
            $fine_id = $_GET['edit'];
            $fine = $conn->query("SELECT * FROM fines WHERE fine_id='$fine_id'")->fetch_assoc();
        ?>
        <h3>Edit Fine</h3>
        <form method="POST" action="">
            <input type="hidden" name="fine_id" value="<?php echo $fine['fine_id']; ?>">
            Loan ID:
            <select name="loan_id" required>
                <?php $loans->data_seek(0); while($l = $loans->fetch_assoc()) { ?>
                    <option value="<?php echo $l['loan_id']; ?>" <?php if($l['loan_id']==$fine['loan_id']) echo 'selected'; ?>><?php echo $l['loan_id']; ?></option>
                <?php } ?>
            </select><br>
            Fine Amount: <input type="number" name="fine_amount" step="0.01" value="<?php echo $fine['fine_amount']; ?>" required><br>
            Is Paid:
            <select name="is_paid" required>
                <option value="yes" <?php if($fine['is_paid']=='yes') echo 'selected'; ?>>yes</option>
                <option value="no" <?php if($fine['is_paid']=='no') echo 'selected'; ?>>no</option>
            </select><br>
            Issued Date: <input type="date" name="issued_date" value="<?php echo $fine['issued_date']; ?>" required><br>
            Paid Date: <input type="date" name="paid_date" value="<?php echo $fine['paid_date']; ?>"><br>
            <input type="submit" name="edit_fine" value="Update Fine">
        </form>
        <?php } else { ?>
        <h3>Add New Fine</h3>
        <form method="POST" action="">
            Loan ID:
            <select name="loan_id" required>
                <?php $loans->data_seek(0); while($l = $loans->fetch_assoc()) { ?>
                    <option value="<?php echo $l['loan_id']; ?>"><?php echo $l['loan_id']; ?></option>
                <?php } ?>
            </select><br>
            Fine Amount: <input type="number" name="fine_amount" step="0.01" required><br>
            Is Paid:
            <select name="is_paid" required>
                <option value="yes">yes</option>
                <option value="no">no</option>
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
            <th>ID</th><th>Loan ID</th><th>Fine Amount</th><th>Is Paid</th><th>Issued Date</th><th>Paid Date</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php
        while($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['fine_id']; ?></td>
            <td><?php echo $row['loan_id']; ?></td>
            <td><?php echo $row['fine_amount']; ?></td>
            <td><?php echo $row['is_paid']; ?></td>
            <td><?php echo $row['issued_date']; ?></td>
            <td><?php echo $row['paid_date']; ?></td>
            <?php if($role != 'member') { ?>
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
