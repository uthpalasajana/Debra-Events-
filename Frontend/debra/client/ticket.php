<?php
// Get eventID and customerID from query parameters
$eventID = $_GET['eventID'];
$customerID = $_GET['customerID'];

// API URLs
$eventApiUrl = "https://localhost:7040/api/Event/$eventID";
$customerApiUrl = "https://localhost:7040/api/Customer/$customerID";
$salesApiUrl = "https://localhost:7040/api/Sale/customer/$customerID";
$ticketApiUrl = "https://localhost:7040/api/Ticket/";

// Initialize cURL session and fetch event details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $eventApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$eventResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session and fetch customer details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $customerApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$customerResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session and fetch sales details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $salesApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$salesResponse = curl_exec($ch);
curl_close($ch);

// Decode responses
$eventData = json_decode($eventResponse, true);
$customerData = json_decode($customerResponse, true);
$salesData = json_decode($salesResponse, true);

$baseImageUrl = "https://localhost:7040/";
// Fetch ticket details for each sale
$tickets = [];
foreach ($salesData as $sale) {
    $ticketID = $sale['ticketID'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ticketApiUrl . $ticketID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
    $ticketResponse = curl_exec($ch);
    curl_close($ch);
    $ticketData = json_decode($ticketResponse, true);
    $tickets[] = array_merge($sale, $ticketData);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <link rel="stylesheet" href="../assets/css/ticket1.css">
</head>
<body>
<?php include("../header.php") ?> 
    <main id="main-content">
        <?php foreach ($tickets as $index => $ticket): ?>
        <div class="ticket" id="ticket-<?php echo $index; ?>">
            <div class="left">
                <img src="<?php echo htmlspecialchars($baseImageUrl . $eventData['eventImage']); ?>" alt="Event Image"/>
            </div>
            <div class="right">
                <div class="ticket-title">
                    <?php echo $eventData['eventName']; ?>
                </div>
                <div class="body-container">
                    <div class="des-container">
                        
                        <div class="des-ticket">
                            <div class="box-event">
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>Time</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $eventData['time']; ?></h5>
                                    </div>
                                </div>
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>Date</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $eventData['date']; ?></h5>
                                    </div>
                                </div>
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>Location</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $eventData['location']; ?></h5>
                                    </div>
                                </div>
                                <!-- Add more event details as needed -->
                            </div>
                        </div>
                    </div>
                    <div class="des-container">
                        
                        <div class="des-ticket">
                            <div class="box-event">
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>Customer Name</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $customerData['name']; ?></h5>
                                    </div>
                                </div>
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>Email</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $customerData['email']; ?></h5>
                                    </div>
                                </div>
                                <div class="des-journey">
                                    <div class="des-title-body">
                                        <h4>NIC</h4>
                                    </div>
                                    <div class="des-content-body">
                                        <h5><?php echo $customerData['nic']; ?></h5>
                                    </div>
                                </div>
                                <!-- Add more customer details as needed -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="des-ticket-bottom">
                    <div class="des-title">
                        <h4><?php echo $ticket['ticketType']; ?></h4>
                    </div>
                    <div class="des-title-bottom">
                        <h4>#<?php echo str_pad($ticket['ticketNumber'], 3, '0', STR_PAD_LEFT); ?></h4>
                    </div>
                    <div class="des-content">
                        <h5>Rs <?php echo number_format($ticket['price'], 2); ?></h5>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
    <button id="download">Download</button>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.0.0/html2canvas.min.js"></script>

</body>
<script>
document.getElementById('download').addEventListener('click', function() {
    const mainElement = document.getElementById('main-content');
    html2canvas(mainElement, { 
        scale: 2,
        useCORS: true
    }).then(canvas => {
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/jpeg', 0.98);
        link.download = 'tickets.jpg';
        link.click();
    }).catch(function(error) {
        console.error('Error generating canvas:', error);
    });
});
</script>
</html>
