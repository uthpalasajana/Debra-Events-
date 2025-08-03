<?php
session_start();
?>

<?php
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

// Function to send a DELETE request to the API
function deleteData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Check if delete request is made
if (isset($_POST['delete'])) {
    $eventID = $_POST['eventID'];
    $deleteUrl = 'https://localhost:7040/api/Event/' . $eventID;
    deleteData($deleteUrl);

    // Reload the page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch events data
$eventsUrl = 'https://localhost:7040/api/Event';
$events = fetchData($eventsUrl);

// Process and display events data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Event Overview</title>
    <link rel="stylesheet" href="../assets/css/adminstyle1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .event-table {
            width: 95%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .event-table th, .event-table td {
            border: 1px solid #ccc;
            padding: 20px;
            font-size: 18px;
            text-align: left;
            background-color: darkgreen;   /*methana wenas kalaaa*/
        }
        .content-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .content-section h1 {
            font-weight: 500;
            font-size: 40px;
        }
        .delete-button {
            background-color: purple;     /*methana wenas kalaaa*/
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>
        <div class="content">
            <div class="content-section">
                <h1>View Events</h1> <!--methana wenas kalaaa-->
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($events) {
                            $count = 1;
                            foreach ($events as $event) {
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$event['eventName']}</td>
                                        <td>{$event['date']}</td>
                                        <td>{$event['location']}</td>
                                        <td>{$event['description']}</td>
                                        <td>
                                            <form method='post' action=''>
                                                <input type='hidden' name='eventID' value='{$event['eventID']}'>
                                                <button type='submit' name='delete' class='delete-button'>Delete</button>
                                            </form>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No data available</td></tr>";
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
