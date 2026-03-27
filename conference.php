<?php 
include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Admin Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="dashboard-container">
        
        <section class="admin-section">
            <h2><i class="icon"></i> Attendee Management</h2>
            <ul>
                <li><a href="attendee_list.php">View Attendees (Students, Professionals, Sponsors)</a></li>
                <li><a href="add_attendee.php">Add New Attendee</a></li>
                <li><a href="hotel_rooms.php">Hotel Room Occupancy</a></li>
            </ul>
        </section>

        <section class="admin-section">
            <h2><i class="icon"></i> Sponsoring Companies</h2>
            <ul>
                <li><a href="sponsors.php">List Sponsors & Levels</a></li>
                <li><a href="company_jobs.php">View Available Jobs by Company</a></li>
                <li><a href="manage_sponsors.php">Add/Delete Sponsoring Company</a></li>
            </ul>
        </section>

        <section class="admin-section">
            <h2><i class="icon"></i> Schedule & Logistics</h2>
            <ul>
                <li><a href="schedule.php">View Daily Schedule</a></li>
                <li><a href="switch_session.php">Switch Session Time/Location</a></li>
                <li><a href="subcommittee.php">View Sub-Committee Members</a></li>
            </ul>
        </section>

        <section class="admin-section" style="background-color: #e8f4fd;">
            <h2><i class="icon"></i> Financial Overview</h2>
            <ul>
                <li><a href="finance.php"><strong>View Total Conference Intake</strong></a></li>
            </ul>
        </section>

    </div>

    <footer style="margin-top: 50px; text-align: center; font-size: 0.8em; color: #777;">
        <p>&copy; 2026 Conference Organizing Committee | CISC 332 Project</p>
    </footer>

</body>
</html>
