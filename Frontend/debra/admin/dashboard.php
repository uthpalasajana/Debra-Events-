<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
    <?php
        include("sidemenu.php")
        ?>
        <div class="content">
            <div id="dashboard" class="content-section">
                <h1>Welcome, Admin User!</h1>
                <canvas id="sales-chart" width="400" height="200"></canvas>
            </div>
            <div id="create-event" class="content-section" style="display:none;">
                <h1>Create New Event</h1>
                <!-- Add form or content for creating a new event here -->
            </div>
            <div id="view-sales" class="content-section" style="display:none;">
                <h1>View Sales</h1>
                <!-- Add content for viewing sales here -->
            </div>
            <div id="booking-details" class="content-section" style="display:none;">
                <h1>Booking Details</h1>
                <!-- Add content for booking details here -->
            </div>
            <div id="partners" class="content-section" style="display:none;">
                <h1>Partners</h1>
                <!-- Add content for partners here -->
            </div>
            
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
