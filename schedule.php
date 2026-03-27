<link rel="stylesheet" href="css/style.css">
<?php
require_once 'db_connect.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Schedule</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Conference Schedule</h1>

    <form method="GET" action="schedule.php">
        <label for="day">Select Conference Day:</label>
        <select name="day" id="day" onchange="this.form.submit()">
            <option value="">-- Choose a Date --</option>
            <?php
            // Dynamically get all unique dates from the session table
            $dateStmt = $pdo->query("SELECT DISTINCT session_date FROM SESSION ORDER BY session_date");
            while ($row = $dateStmt->fetch()) {
                $selected = ($_GET['day'] == $row['session_date']) ? "selected" : "";
                echo "<option value='{$row['session_date']}' $selected>{$row['session_date']}</option>";
            }
            ?>
        </select>
    </form>

    <?php
    if (isset($_GET['day']) && !empty($_GET['day'])) {
        $selectedDay = $_GET['day'];
        
        try {
            // Join with SPEAKER to show who is talking (addressing Part 2 feedback)
            $sql = "SELECT s.title, s.start_time, s.end_time, s.location, sp.first_name, sp.last_name 
                    FROM SESSION s
                    LEFT JOIN SPEAKER sp ON s.speaker_id = sp.speaker_id
                    WHERE s.session_date = ?
                    ORDER BY s.start_time ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$selectedDay]);
            $sessions = $stmt->fetchAll();

            if ($sessions) {
                echo "<h2>Schedule for " . htmlspecialchars($selectedDay) . "</h2>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Session Title</th>
                                <th>Speaker</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($sessions as $row) {
                    $speakerName = ($row['first_name']) ? $row['first_name'] . " " . $row['last_name'] : "TBD";
                    echo "<tr>
                            <td>" . date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time'])) . "</td>
                            <td>{$row['title']}</td>
                            <td>{$speakerName}</td>
                            <td>{$row['location']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No sessions found for this day.</p>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>
