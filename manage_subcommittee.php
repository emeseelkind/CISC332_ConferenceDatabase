<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sub-Committee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Manage Sub-Committee Members</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Add a new committee member
        if (isset($_POST['add_member'])) {
            $first_name     = trim($_POST['first_name']);
            $last_name      = trim($_POST['last_name']);
            $committee_name = $_POST['committee_name'];

            if ($first_name !== '' && $last_name !== '') {
                $insert = $pdo->prepare("INSERT INTO COMMITTEE_MEMBER (first_name, last_name, committee_name) VALUES (?, ?, ?)");
                $insert->execute([$first_name, $last_name, $committee_name]);
                echo "<p style='color:green;'>Committee member added successfully.</p>";
            } else {
                echo "<p style='color:red;'>First and last name cannot be empty.</p>";
            }
        }

        // Delete a committee member
        if (isset($_POST['delete_member'])) {
            $member_id = $_POST['delete_member_id'];
            if ($member_id !== '') {
                $del = $pdo->prepare("DELETE FROM COMMITTEE_MEMBER WHERE member_id = ?");
                $del->execute([$member_id]);
                echo "<p style='color:green;'>Committee member deleted successfully.</p>";
            }
        }
    }
    ?>

    <!-- Add Member -->
    <section>
        <h2>Add New Committee Member</h2>
        <form method="POST" action="manage_subcommittee.php">
            <input type="hidden" name="add_member" value="1">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
            <label>Sub-Committee:</label>
            <select name="committee_name" required>
                <option value="">-- Select Committee --</option>
                <?php
                $committees = $pdo->query("SELECT committee_name FROM SUB_COMMITTEE ORDER BY committee_name");
                while ($row = $committees->fetch()) {
                    echo "<option value='" . htmlspecialchars($row['committee_name']) . "'>" . htmlspecialchars($row['committee_name']) . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Add Member">
        </form>
    </section>

    <!-- Delete Member -->
    <section>
        <h2>Delete Committee Member</h2>
        <form method="POST" action="manage_subcommittee.php">
            <label>Select Member to Remove:</label>
            <select name="delete_member_id" required>
                <option value="">-- Choose --</option>
                <?php
                $members = $pdo->query("SELECT member_id, first_name, last_name, committee_name FROM COMMITTEE_MEMBER ORDER BY committee_name, last_name");
                while ($row = $members->fetch()) {
                    $label = htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' (' . $row['committee_name'] . ')');
                    echo "<option value='" . $row['member_id'] . "'>" . $label . "</option>";
                }
                ?>
            </select>
            <input type="submit" name="delete_member" value="Delete Member"
                onclick="return confirm('Are you sure you want to remove this committee member?');">
        </form>
    </section>

    <!-- Current Members Table -->
    <section>
        <h2>Current Sub-Committee Members</h2>
        <?php
        $allMembers = $pdo->query("SELECT * FROM COMMITTEE_MEMBER ORDER BY committee_name, last_name")->fetchAll();
        if ($allMembers) {
            echo "<table><thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Committee</th></tr></thead><tbody>";
            foreach ($allMembers as $m) {
                echo "<tr>
                    <td>" . htmlspecialchars($m['member_id']) . "</td>
                    <td>" . htmlspecialchars($m['first_name']) . "</td>
                    <td>" . htmlspecialchars($m['last_name']) . "</td>
                    <td>" . htmlspecialchars($m['committee_name']) . "</td>
                </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No committee members found.</p>";
        }
        ?>
    </section>

</body>
</html>