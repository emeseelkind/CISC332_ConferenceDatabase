<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sponsors</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Sponsor Companies</h1>

    <?php
    $stmt = $pdo->query("SELECT company_name, sponsor_level, sponsorship_amount FROM COMPANY ORDER BY sponsor_level DESC, company_name");
    $companies = $stmt->fetchAll();

    if ($companies) {
        echo "<table><thead><tr><th>Company</th><th>Sponsor Level</th><th>Sponsorship Amount</th></tr></thead><tbody>";
        foreach ($companies as $company) {
            echo "<tr><td>" . htmlspecialchars($company['company_name']) . "</td><td>" . htmlspecialchars($company['sponsor_level']) . "</td><td>$" . number_format($company['sponsorship_amount'], 2) . "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No sponsors found.</p>";
    }
    ?>
</body>
</html>
