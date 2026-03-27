<link rel="stylesheet" href="css/style.css">

<?php
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Overview</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Conference Financial Summary</h1>

    <?php
    try {
        // 1. Get Total Registration Intake from Attendees
        $columns = $pdo->query("SHOW COLUMNS FROM ATTENDEE")->fetchAll(PDO::FETCH_COLUMN);
        if (in_array('total_paid', $columns)) {
            $regQuery = $pdo->query("SELECT SUM(total_paid) as total_reg FROM ATTENDEE");
        } elseif (in_array('rate', $columns)) {
            $regQuery = $pdo->query("SELECT SUM(rate) as total_reg FROM ATTENDEE");
        } else {
            $regQuery = $pdo->query("SELECT 0 as total_reg");
        }
        $regData = $regQuery->fetch();
        $totalReg = $regData['total_reg'] ?? 0;

        // 2. Get Total Sponsorship Intake from Companies (only active sponsor attendees)
        $sponQuery = $pdo->query(
            "SELECT SUM(c.sponsorship_amount) as total_spon 
             FROM COMPANY c 
             JOIN ATTENDEE a ON c.company_name = a.company_name 
             WHERE a.attendee_type = 'Sponsor'"
        );
        $sponData = $sponQuery->fetch();
        $totalSpon = $sponData['total_spon'] ?? 0;

        $grandTotal = $totalReg + $totalSpon;

        echo "<table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Registration Fees (Attendees)</td>
                        <td>$" . number_format($totalReg, 2) . "</td>
                    </tr>
                    <tr>
                        <td>Total Sponsorship Funds (Companies)</td>
                        <td>$" . number_format($totalSpon, 2) . "</td>
                    </tr>
                    <tr style='font-weight:bold; background-color:#eee;'>
                        <td>GRAND TOTAL INTAKE</td>
                        <td>$" . number_format($grandTotal, 2) . "</td>
                    </tr>
                </tbody>
              </table>";

    } catch (PDOException $e) {
        echo "<p class='error'>Error calculating finances: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
