<link rel="stylesheet" href="css/style.css">

<?php
include 'header.php';
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- ADD ATTENDEE ---
    if (isset($_POST['add_attendee'])) {
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $email = $_POST['email'];
        $type  = $_POST['attendee_type'];
        $room  = null;
        $company_name = null;

        try {
            if ($type == 'Student') {
                $roomQuery = "SELECT r.room_number 
                              FROM HOTEL_ROOM r 
                              LEFT JOIN ATTENDEE a ON r.room_number = a.room_number 
                              GROUP BY r.room_number 
                              HAVING COUNT(a.attendee_id) < MAX(r.number_of_beds) 
                              LIMIT 1";
                $stmt = $pdo->query($roomQuery);
                $availableRoom = $stmt->fetch();

                if ($availableRoom) {
                    $room = $availableRoom['room_number'];
                } else {
                    echo "<p style='color:red;'>Error: No hotel rooms available for this student!</p>";
                    exit;
                }
            }

            if ($type === 'Sponsor') {
                $company_name = $_POST['company_name'] ?? null;
            }

            if ($type === 'Student') {
                $total_paid = 50.00;
            } elseif ($type === 'Professional') {
                $total_paid = 100.00;
            } else {
                $total_paid = 0.00;
            }

            $sql = "INSERT INTO ATTENDEE (first_name, last_name, email, attendee_type, room_number, company_name, total_paid) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$fname, $lname, $email, $type, $room, $company_name, $total_paid]);

            echo "<p style='color:green;'>Attendee added successfully! Assigned to Room: " . ($room ?? 'N/A') . "</p>";

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo "<p style='color:red;'>An attendee with that email already exists.</p>";
            } else {
                echo "<p style='color:red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }

    // --- DELETE ATTENDEE ---
    if (isset($_POST['delete_attendee'])) {
        $attendee_id = $_POST['delete_attendee_id'];
        if ($attendee_id !== '') {
            try {
                $del = $pdo->prepare("DELETE FROM ATTENDEE WHERE attendee_id = ?");
                $del->execute([$attendee_id]);

                if ($del->rowCount() > 0) {
                    echo "<p style='color:green;'>Attendee deleted successfully.</p>";
                } else {
                    echo "<p style='color:red;'>No attendee found. They may have already been deleted.</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Please select an attendee to delete.</p>";
        }
    }
}
?>

<h2>Register New Attendee</h2>
<form method="POST" action="">
    <input type="hidden" name="add_attendee" value="1">

    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Type:</label>
    <select name="attendee_type" id="attendee_type">
        <option value="Student">Student</option>
        <option value="Professional">Professional</option>
        <option value="Sponsor">Sponsor</option>
    </select>

    <div id="company_field" style="display:none;">
        <label>Company:</label>
        <select name="company_name">
            <?php
            $companies = $pdo->query("SELECT company_name, sponsor_level FROM COMPANY ORDER BY company_name");
            while ($c = $companies->fetch()) {
                echo "<option value='" . htmlspecialchars($c['company_name']) . "'>" . htmlspecialchars($c['company_name'] . ' (' . $c['sponsor_level'] . ')') . "</option>";
            }
            ?>
        </select>
    </div>

    <input type="submit" value="Register Attendee">
</form>

<section>
    <h2>Delete Attendee</h2>
    <form method="POST" action="">
        <label>Select Attendee to Remove:</label>
        <select name="delete_attendee_id" required>
            <option value="">-- Choose --</option>
            <?php
            $attendees = $pdo->query("SELECT attendee_id, first_name, last_name, email, attendee_type FROM ATTENDEE ORDER BY last_name, first_name");
            while ($row = $attendees->fetch()) {
                $label = htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' (' . $row['email'] . ' — ' . $row['attendee_type'] . ')');
                echo "<option value='" . $row['attendee_id'] . "'>" . $label . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="delete_attendee" value="Delete Attendee"
            onclick="return confirm('Are you sure you want to remove this attendee?');">
    </form>
</section>

<script>
document.getElementById('attendee_type').addEventListener('change', function() {
    var companyField = document.getElementById('company_field');
    if (this.value === 'Sponsor') {
        companyField.style.display = 'block';
    } else {
        companyField.style.display = 'none';
    }
});
</script>