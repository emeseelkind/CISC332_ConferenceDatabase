<link rel="stylesheet" href="css/style.css">
<?php 
require_once 'db_connect.php'; 
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Room Occupancy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="conference.php">← Back to Dashboard</a>
    <h1>Hotel Room Occupancy</h1>

    <form method="GET" action="hotel_rooms.php">
        <label for="room">Select Room Number:</label>
        <select name="room" id="room" required>
            <option value="">-- Choose a room --</option>
            <?php
            $roomStmt = $pdo->query("SELECT room_number FROM HOTEL_ROOM ORDER BY room_number");
            while ($room = $roomStmt->fetch()) {
                $sel = (isset($_GET['room']) && $_GET['room'] == $room['room_number']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($room['room_number']) . "' $sel>" . htmlspecialchars($room['room_number']) . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Show Students">
    </form>

    <?php
    if (!empty($_GET['room'])) {
        $roomNumber = $_GET['room'];
        $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM ATTENDEE WHERE attendee_type = 'Student' AND room_number = ? ORDER BY last_name, first_name");
        $stmt->execute([$roomNumber]);
        $students = $stmt->fetchAll();

        if ($students) {
            echo "<h2>Students in Room " . htmlspecialchars($roomNumber) . "</h2>";
            echo "<table><thead><tr><th>Name</th><th>Email</th></tr></thead><tbody>";
            foreach ($students as $student) {
                echo "<tr><td>" . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . "</td><td>" . htmlspecialchars($student['email']) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No students currently assigned to this room.</p>";
        }
    }
    ?>
</body>
</html>
