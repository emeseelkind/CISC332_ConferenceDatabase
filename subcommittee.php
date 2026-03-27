<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub-Committee Members</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Sub-Committee Members</h1>

    <form method="GET" action="subcommittee.php">
        <label for="committee">Select Sub-Committee:</label>
        <select name="committee" id="committee" required>
            <option value="">-- Choose a committee --</option>
            <?php
            $commStmt = $pdo->query("SELECT committee_name FROM COMMITTEE ORDER BY committee_name");
            while ($comm = $commStmt->fetch()) {
                $sel = (isset($_GET['committee']) && $_GET['committee'] === $comm['committee_name']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($comm['committee_name']) . "' $sel>" . htmlspecialchars($comm['committee_name']) . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Show Members">
    </form>

    <?php
    if (!empty($_GET['committee'])) {
        $committee = $_GET['committee'];
        $query = "SELECT m.member_id, m.first_name, m.last_name, cm.is_chair FROM COMMITTEE_MEMBER m
                  JOIN COMMITTEE_MEMBERSHIP cm ON m.member_id = cm.member_id
                  WHERE cm.committee_name = ? ORDER BY m.last_name, m.first_name";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$committee]);
        $members = $stmt->fetchAll();

        if ($members) {
            echo "<h2>Members of " . htmlspecialchars($committee) . "</h2>";
            echo "<table><thead><tr><th>ID</th><th>Name</th><th>Chair</th></tr></thead><tbody>";
            foreach ($members as $member) {
                echo "<tr><td>" . $member['member_id'] . "</td><td>" . htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) . "</td><td>" . ($member['is_chair'] ? 'Yes' : 'No') . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No members found for this committee.</p>";
        }
    }
    ?>
</body>
</html>
