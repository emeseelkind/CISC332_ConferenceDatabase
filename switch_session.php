<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Switch Session</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Switch Session Date/Time/Location</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['session_id'])) {
        $session_id = $_POST['session_id'];
        $date = $_POST['session_date'];
        $start = $_POST['start_time'];
        $end = $_POST['end_time'];
        $location = $_POST['location'];

        $update = $pdo->prepare("UPDATE SESSION SET session_date = ?, start_time = ?, end_time = ?, location = ? WHERE session_id = ?");
        $update->execute([$date, $start, $end, $location, $session_id]);

        echo "<p style='color:green;'>Session updated successfully.</p>";
    }

    $sessions = $pdo->query("SELECT session_id, title, session_date, start_time, end_time, location FROM SESSION ORDER BY session_date, start_time")->fetchAll();
    ?>

    <form method="POST" action="switch_session.php">
        <label for="session_id">Select Session:</label>
        <select name="session_id" id="session_id" required onchange="updateForm()">
            <option value="">-- Choose --</option>
            <?php
            foreach ($sessions as $s) {
                echo "<option value='" . $s['session_id'] . "' data-date='" . $s['session_date'] . "' data-start='" . $s['start_time'] . "' data-end='" . $s['end_time'] . "' data-location='" . htmlspecialchars($s['location']) . "'>" . htmlspecialchars($s['title'] . ' (' . $s['session_date'] . ')') . "</option>";
            }
            ?>
        </select>

        <label for="session_date">New Date:</label>
        <input type="date" name="session_date" id="session_date" required>

        <label for="start_time">New Start Time:</label>
        <input type="time" name="start_time" id="start_time" required>

        <label for="end_time">New End Time:</label>
        <input type="time" name="end_time" id="end_time" required>

        <label for="location">New Location:</label>
        <input type="text" name="location" id="location" required>

        <input type="submit" value="Update Session">
    </form>

    <script>
    function updateForm() {
        var sel = document.getElementById('session_id');
        var option = sel.options[sel.selectedIndex];
        if (!option || !option.value) return;
        document.getElementById('session_date').value = option.getAttribute('data-date');
        document.getElementById('start_time').value = option.getAttribute('data-start');
        document.getElementById('end_time').value = option.getAttribute('data-end');
        document.getElementById('location').value = option.getAttribute('data-location');
    }
    </script>

    <h2>All Sessions</h2>
    <table>
        <thead><tr><th>ID</th><th>Title</th><th>Date</th><th>Start</th><th>End</th><th>Room</th></tr></thead>
        <tbody>
        <?php foreach ($sessions as $s) {
            echo "<tr><td>" . $s['session_id'] . "</td><td>" . htmlspecialchars($s['title']) . "</td><td>" . htmlspecialchars($s['session_date']) . "</td><td>" . htmlspecialchars($s['start_time']) . "</td><td>" . htmlspecialchars($s['end_time']) . "</td><td>" . htmlspecialchars($s['location']) . "</td></tr>";
        } ?>
        </tbody>
    </table>
</body>
</html>
