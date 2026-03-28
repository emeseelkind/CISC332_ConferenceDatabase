<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sponsors</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Manage Sponsoring Companies</h1>

    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_company'])) {
        $company_name = trim($_POST['company_name']);
        $level = $_POST['sponsor_level'];
        $emails = $_POST['emails_available'];

        switch ($level) {
            case 'Platinum':
                $amount = 10000.00;
                break;
            case 'Gold':
                $amount = 5000.00;
                break;
            case 'Silver':
                $amount = 3000.00;
                break;
            case 'Bronze':
            default:
                $amount = 1000.00;
                break;
        }

        if ($company_name !== '') {
            try {
                $insert = $pdo->prepare("INSERT INTO COMPANY (company_name, sponsor_level, sponsorship_amount, emails_available) VALUES (?, ?, ?, ?)");
                $insert->execute([$company_name, $level, $amount, $emails]);
                echo "<p style='color:green;'>Company added successfully.</p>";
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    echo "<p style='color:red;'>A company named <strong>" . htmlspecialchars($company_name) . "</strong> already exists. Please use a unique company name.</p>";
                } else {
                    echo "<p style='color:red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        } else {
            echo "<p style='color:red;'>Company name cannot be empty.</p>";
        }
    } // ← closes add_company

    if (isset($_POST['delete_company'])) {
        $delCompany = trim($_POST['delete_company_name']);
        if ($delCompany !== '') {
            try {
                $pdo->prepare("DELETE FROM ATTENDEE WHERE company_name = ?")->execute([$delCompany]);
                $pdo->prepare("DELETE FROM JOB_AD WHERE company_name = ?")->execute([$delCompany]);
                $del = $pdo->prepare("DELETE FROM COMPANY WHERE company_name = ?");
                $del->execute([$delCompany]);

                if ($del->rowCount() > 0) {
                    echo "<p style='color:green;'>Company <strong>" . htmlspecialchars($delCompany) . "</strong> and associated records deleted successfully.</p>";
                } else {
                    echo "<p style='color:red;'>No company found with that name. It may have already been deleted.</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Please select a company to delete.</p>";
        }
    } 

} 
?>

    <section>
        <h2>Add New Sponsor</h2>
        <form method="POST" action="manage_sponsors.php">
            <input type="hidden" name="add_company" value="1">
            <label>Company Name:</label>
            <input type="text" name="company_name" required>
            <label>Sponsor Level:</label>
            <select name="sponsor_level" required>
                <option value="Platinum">Platinum ($10,000)</option>
                <option value="Gold">Gold ($5,000)</option>
                <option value="Silver">Silver ($3,000)</option>
                <option value="Bronze">Bronze ($1,000)</option>
            </select>
            <p style="margin: 8px 0; font-size: 0.9em; color: #333;">Amount is set automatically from sponsor level.</p>
            <label>Emails Available:</label>
            <input type="number" name="emails_available" min="0" value="0" required>
            <input type="submit" value="Add Sponsor">
        </form>
    </section>

    <section>
        <h2>Delete Sponsor</h2>
        <form method="POST" action="manage_sponsors.php">
            <label>Select Company to Remove:</label>
            <select name="delete_company_name" required>
                <option value="">-- Choose --</option>
                <?php
                $companyStmt = $pdo->query("SELECT company_name FROM COMPANY ORDER BY company_name");
                while ($row = $companyStmt->fetch()) {
                    echo "<option value='" . htmlspecialchars($row['company_name']) . "'>" . htmlspecialchars($row['company_name']) . "</option>";
                }
                ?>
            </select>
            <input type="submit" name="delete_company" value="Delete Company" onclick="return confirm('Are you sure? This will also remove all attendees and job ads for this company.');">        </form>
    </section>

    <section>
        <h2>Current Sponsor Companies</h2>
        <?php
        $allSponsors = $pdo->query("SELECT * FROM COMPANY ORDER BY company_name")->fetchAll();
        if ($allSponsors) {
            echo "<table><thead><tr><th>Company</th><th>Level</th><th>Amount</th><th>Emails</th></tr></thead><tbody>";
            foreach ($allSponsors as $s) {
                echo "<tr><td>" . htmlspecialchars($s['company_name']) . "</td><td>" . htmlspecialchars($s['sponsor_level']) . "</td><td>$" . number_format($s['sponsorship_amount'], 2) . "</td><td>" . htmlspecialchars($s['emails_available']) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No sponsor companies available.</p>";
        }
        ?>
    </section>
</body>
</html>
