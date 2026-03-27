<link rel="stylesheet" href="css/style.css">

<?php
include 'header.php';
include 'db_connect.php'; // This file contains your $pdo connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $type  = $_POST['attendee_type'];
    $room  = null;
    $company_name = null;

    try {
        // If they are a student, find an available room
        if ($type == 'Student') {
            // Logic: Find rooms where the current count of occupants is less than the bed count
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

        // If sponsor, get company name
        if ($type === 'Sponsor') {
            $company_name = $_POST['company_name'] ?? null;
        }

        // set attendee payment by type (Student: $50, Professional: $100, Sponsor: $0)
        if ($type === 'Student') {
            $total_paid = 50.00;
        } elseif ($type === 'Professional') {
            $total_paid = 100.00;
        } else {
            $total_paid = 0.00;
        }

        // Insert the attendee using Prepared Statements (Security Best Practice)
        $sql = "INSERT INTO ATTENDEE (first_name, last_name, email, attendee_type, room_number, company_name, total_paid) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fname, $lname, $email, $type, $room, $company_name, $total_paid]);

        echo "<p style='color:green;'>Attendee added successfully! Assigned to Room: " . ($room ?? 'N/A') . "</p>";

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<h2>Register New Attendee</h2>
<form method="POST" action="">
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
