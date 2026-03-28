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

    <h2>Add Schedule Event</h2>
    <form method="POST" action="schedule.php">
        <label for="session_date">Date:</label>
        <input type="date" name="session_date" id="session_date" required><br><br>
        
        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" required><br><br>
        
        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" required><br><br>
        
        <label for="title">Session Title:</label>
        <input type="text" name="title" id="title" required><br><br>
        
        <label for="speaker_id">Speaker:</label>
        <select name="speaker_id" id="speaker_id">
            <option value="">-- Select Speaker --</option>
            <?php
            $speakerStmt = $pdo->query("SELECT speaker_id, first_name, last_name FROM SPEAKER ORDER BY last_name");
            while ($speaker = $speakerStmt->fetch()) {
                echo "<option value='{$speaker['speaker_id']}'>{$speaker['first_name']} {$speaker['last_name']}</option>";
            }
            ?>
        </select><br><br>
        
        <label for="location">Location:</label>
        <input type="text" name="location" id="location" required><br><br>
        
        <input type="submit" value="Add Event">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $session_date = $_POST['session_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $title = $_POST['title'];
        $speaker_id = $_POST['speaker_id'] ?: null;
        $location = $_POST['location'];
        
        try {
            $insertStmt = $pdo->prepare("INSERT INTO SESSION (title, start_time, end_time, session_date, location, speaker_id) VALUES (?, ?, ?, ?, ?, ?)");
            $insertStmt->execute([$title, $start_time, $end_time, $session_date, $location, $speaker_id]);
            echo "<p>Event added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p>Error adding event: " . $e->getMessage() . "</p>";
        }
    }
    ?>

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
