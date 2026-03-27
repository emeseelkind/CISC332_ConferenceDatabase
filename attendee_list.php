<link rel="stylesheet" href="css/style.css">

<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendee Lists</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Conference Attendees by Type</h1>

    <?php
    $types = ['Student', 'Professional', 'Sponsor'];

    foreach ($types as $type) {
        if ($type === 'Sponsor') {
            $stmt = $pdo->prepare("SELECT a.first_name, a.last_name, a.email, a.company_name, c.sponsor_level, c.sponsorship_amount FROM ATTENDEE a LEFT JOIN COMPANY c ON a.company_name = c.company_name WHERE a.attendee_type = ? ORDER BY a.last_name, a.first_name");
        } else {
            $stmt = $pdo->prepare("SELECT first_name, last_name, email, room_number FROM ATTENDEE WHERE attendee_type = ? ORDER BY last_name, first_name");
        }
        $stmt->execute([$type]);
        $rows = $stmt->fetchAll();

        echo "<h2>" . htmlspecialchars($type) . "s</h2>";

        if ($rows) {
            echo "<table><thead><tr><th>Name</th><th>Email</th>";
            if ($type === 'Student') echo "<th>Room</th>";
            if ($type === 'Sponsor') echo "<th>Company</th><th>Level</th><th>Package</th>";
            echo "</tr></thead><tbody>";
            foreach ($rows as $row) {
                echo "<tr><td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                if ($type === 'Student') {
                    echo "<td>" . htmlspecialchars($row['room_number'] ?? 'N/A') . "</td>";
                } elseif ($type === 'Sponsor') {
                    $level = $row['sponsor_level'] ?? 'Unknown';
                    $package = $row['sponsorship_amount'] ?? 0;
                    echo "<td>" . htmlspecialchars($row['company_name'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($level) . "</td>";
                    echo "<td>$" . number_format($package, 2) . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No " . htmlspecialchars(strtolower($type)) . "s found.</p>";
        }
    }
    ?>
</body>
</html>
