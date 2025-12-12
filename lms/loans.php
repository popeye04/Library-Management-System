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

// Handle Add Loan (Admin & Librarian only)
if($role != 'member' && isset($_POST['add_loan'])) {
    $member_id = $_POST['member_id'];
    $copy_id = $_POST['copy_id'];
    $borrowed_date = $_POST['borrowed_date'];
    $due_date = $_POST['due_date'];
    $returned_date = $_POST['returned_date'] ? $_POST['returned_date'] : NULL;

    $sql = "INSERT INTO loans (member_id, copy_id, borrowed_date, due_date, returned_date)
            VALUES ('$member_id', '$copy_id', '$borrowed_date', '$due_date', '$returned_date')";
    $conn->query($sql);
}

// Handle Delete Loan (Admin & Librarian only)
if($role != 'member' && isset($_GET['delete'])) {
    $loan_id = $_GET['delete'];
    $conn->query("DELETE FROM loans WHERE loan_id='$loan_id'");
}

// Handle Edit Loan (Admin & Librarian only)
if($role != 'member' && isset($_POST['edit_loan'])) {
    $loan_id = $_POST['loan_id'];
    $member_id = $_POST['member_id'];
    $copy_id = $_POST['copy_id'];
    $borrowed_date = $_POST['borrowed_date'];
    $due_date = $_POST['due_date'];
    $returned_date = $_POST['returned_date'] ? $_POST['returned_date'] : NULL;

    $conn->query("UPDATE loans SET member_id='$member_id', copy_id='$copy_id', borrowed_date='$borrowed_date', due_date='$due_date', returned_date='$returned_date' WHERE loan_id='$loan_id'");
}

// Handle Search
$search = '';
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    if($role == 'member') {
        $query = "SELECT * FROM loans WHERE member_id='$user_id' AND copy_id LIKE '%$search%' ORDER BY loan_id DESC";
    } else {
        $query = "SELECT * FROM loans WHERE copy_id LIKE '%$search%' ORDER BY loan_id DESC";
    }
} else {
    if($role == 'member') {
        $query = "SELECT * FROM loans WHERE member_id='$user_id' ORDER BY loan_id DESC";
    } else {
        $query = "SELECT * FROM loans ORDER BY loan_id DESC";
    }
}

$result = $conn->query($query);

// Fetch members and copies for dropdowns (Admin & Librarian only)
if($role != 'member') {
    $members = $conn->query("SELECT member_id, full_name FROM members");
    $copies = $conn->query("SELECT copy_id FROM book_copies");
}
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
        select, input[type=text], input[type=number], input[type=date] { padding:5px; margin:5px 0; width:200px; }
        input[type=submit] { padding:5px 10px; margin-top:10px; }
        .edit-link, .delete-link { margin-right:10px; color:#007BFF; text-decoration:none; }
        .delete-link:hover { color:red; }
    </style>
</head>
<body>
    <h2>Manage Loans</h2>

    <!-- Search -->
    <form method="GET" action="">
        <input type="number" name="search" placeholder="Search by Copy ID" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Add / Edit Loan Form (Admin & Librarian only) -->
    <?php if($role != 'member') { ?>
        <?php
        if(isset($_GET['edit'])) {
            $loan_id = $_GET['edit'];
            $loan = $conn->query("SELECT * FROM loans WHERE loan_id='$loan_id'")->fetch_assoc();
        ?>
        <h3>Edit Loan</h3>
        <form method="POST" action="">
            <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
            Member:
            <select name="member_id" required>
                <?php $members->data_seek(0); while($m = $members->fetch_assoc()) { ?>
                    <option value="<?php echo $m['member_id']; ?>" <?php if($m['member_id']==$loan['member_id']) echo 'selected'; ?>><?php echo $m['full_name']; ?></option>
                <?php } ?>
            </select><br>
            Copy ID:
            <select name="copy_id" required>
                <?php $copies->data_seek(0); while($c = $copies->fetch_assoc()) { ?>
                    <option value="<?php echo $c['copy_id']; ?>" <?php if($c['copy_id']==$loan['copy_id']) echo 'selected'; ?>><?php echo $c['copy_id']; ?></option>
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
                <?php $members->data_seek(0); while($m = $members->fetch_assoc()) { ?>
                    <option value="<?php echo $m['member_id']; ?>"><?php echo $m['full_name']; ?></option>
                <?php } ?>
            </select><br>
            Copy ID:
            <select name="copy_id" required>
                <?php $copies->data_seek(0); while($c = $copies->fetch_assoc()) { ?>
                    <option value="<?php echo $c['copy_id']; ?>"><?php echo $c['copy_id']; ?></option>
                <?php } ?>
            </select><br>
            Borrowed Date: <input type="date" name="borrowed_date" required><br>
            Due Date: <input type="date" name="due_date" required><br>
            Returned Date: <input type="date" name="returned_date"><br>
            <input type="submit" name="add_loan" value="Add Loan">
        </form>
        <?php } ?>
    <?php } ?>

    <!-- Loans List Table -->
    <h3>Loans List</h3>
    <table>
        <tr>
            <th>ID</th><th>Member</th><th>Copy ID</th><th>Borrowed Date</th><th>Due Date</th><th>Returned Date</th>
            <?php if($role != 'member') echo "<th>Actions</th>"; ?>
        </tr>
        <?php
        while($row = $result->fetch_assoc()) {
            $member_name = $conn->query("SELECT full_name FROM members WHERE member_id='".$row['member_id']."'")->fetch_assoc()['full_name'];
        ?>
        <tr>
            <td><?php echo $row['loan_id']; ?></td>
            <td><?php echo $member_name; ?></td>
            <td><?php echo $row['copy_id']; ?></td>
            <td><?php echo $row['borrowed_date']; ?></td>
            <td><?php echo $row['due_date']; ?></td>
            <td><?php echo $row['returned_date']; ?></td>
            <?php if($role != 'member') { ?>
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
