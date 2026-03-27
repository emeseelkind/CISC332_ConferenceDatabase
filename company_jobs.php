<link rel="stylesheet" href="css/style.css">
<?php
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Jobs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Jobs by Company</h1>

    <form method="GET" action="company_jobs.php">
        <label for="company_name">Select Company:</label>
        <select name="company_name" id="company_name" required>
            <option value="">-- Choose a company --</option>
            <?php
            $companyStmt = $pdo->query("SELECT company_name FROM COMPANY ORDER BY company_name");
            while ($company = $companyStmt->fetch()) {
                $sel = (isset($_GET['company_name']) && $_GET['company_name'] === $company['company_name']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($company['company_name']) . "' $sel>" . htmlspecialchars($company['company_name']) . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Show Jobs">
    </form>

    <?php
    if (!empty($_GET['company_name'])) {
        $companyName = $_GET['company_name'];
        $stmt = $pdo->prepare("SELECT job_id, title, pay_rate, city FROM JOB_AD WHERE company_name = ? ORDER BY title");
        $stmt->execute([$companyName]);
        $jobs = $stmt->fetchAll();

        if ($jobs) {
            echo "<h2>Jobs at " . htmlspecialchars($companyName) . "</h2>";
            echo "<table><thead><tr><th>Job ID</th><th>Title</th><th>Pay Rate</th><th>City</th></tr></thead><tbody>";
            foreach ($jobs as $job) {
                echo "<tr><td>" . $job['job_id'] . "</td><td>" . htmlspecialchars($job['title']) . "</td><td>$" . number_format($job['pay_rate'], 2) . "</td><td>" . htmlspecialchars($job['city']) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No jobs found for " . htmlspecialchars($companyName) . ".</p>";
        }
    }

    echo "<h2>All Available Jobs</h2>";
    $allJobsStmt = $pdo->query("SELECT job_id, title, pay_rate, city, company_name FROM JOB_AD ORDER BY company_name, title");
    $allJobs = $allJobsStmt->fetchAll();
    if ($allJobs) {
        echo "<table><thead><tr><th>Job ID</th><th>Title</th><th>Company</th><th>Pay Rate</th><th>City</th></tr></thead><tbody>";
        foreach ($allJobs as $job) {
            echo "<tr><td>" . $job['job_id'] . "</td><td>" . htmlspecialchars($job['title']) . "</td><td>" . htmlspecialchars($job['company_name']) . "</td><td>$" . number_format($job['pay_rate'], 2) . "</td><td>" . htmlspecialchars($job['city']) . "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No jobs in the system.</p>";
    }
    ?>
</body>
</html>
