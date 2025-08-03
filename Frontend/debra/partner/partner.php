<?php
session_start();

// Function to fetch data from API using cURL
function fetchData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Fetch partner details by email from session
if (isset($_SESSION['user']['email'])) {
    $partnerEmail = $_SESSION['user']['email'];
    $partnerUrl = 'https://localhost:7040/api/Partner/email/' . urlencode($partnerEmail);
    $partnerData = fetchData($partnerUrl);

    if ($partnerData) {
        $partnerID = $partnerData['partnerID'];
        
        // Fetch commissions of specific partner using partnerID
        $commissionUrl = 'https://localhost:7040/api/Commission/partner/' . $partnerID;
        $commissions = fetchData($commissionUrl);
    } else {
        $commissions = null; // Handle case where partner data is not found
    }
} else {
    $commissions = null; // Handle case where session email is not set
}

// Function to fetch event name by event ID
function getEventName($eventID) {
    $eventUrl = 'https://localhost:7040/api/Event/' . $eventID;
    $eventData = fetchData($eventUrl);
    return $eventData['eventName'];
}

// Process and display commission data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Event Overview</title>
    <link rel="stylesheet" href="../assets/css/admin1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .event-table {
            width: 95%;

            margin-top: 40px;
            
            
        }
        .event-table th, .event-table td {
            border: 1px solid #ccc;
            padding: 20px;
            font-size: 18spx;
            text-align: left;
            background-color: darkgreen;   /*methana wenas kalaaa*/
        }
        .content-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .content-section h1{
            font-weight: 500;
            font-size: 40px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>
        <div class="content">
            <div class="content-section">
                <h1>My Sales</h1>
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Name</th>
                            <th>Commission Rate</th>
                            <th>Commission Amount for Debra (Rs)</th>
                            <th>Sales (Rs)</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($commissions) {
                            $count = 1;
                            foreach ($commissions as $commission) {
                                $eventName = getEventName($commission['eventID']);
                                $commissionAmount = $commission['commissionRate'] * $commission['totalSales'];
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$eventName}</td>
                                        <td>{$commission['commissionRate']}</td>
                                      
                                        <td>{$commissionAmount}</td>
                                          <td>{$commission['totalSales']}</td>
                                      </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='5'>No commissions available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
