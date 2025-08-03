<?php
// API URL
$url = "https://localhost:7040/api/Event";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL Verification if using self-signed certificates

// Execute cURL session and get the response
$response = curl_exec($ch);

// Check for cURL errors
if($response === FALSE){
    die('cURL Error: ' . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode JSON response to PHP array
$events = json_decode($response, true);

// Check if decoding was successful
if ($events === null) {
    die('Error decoding JSON');
}

// Define base URL for images
$baseImageUrl = "https://localhost:7040/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <link rel="stylesheet" href="assets/css/stylesheet1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<div class="navbar">
<a href="index.php"><div class="heading">
           Debra Events
        </div></a> 
        <div class="links">
            <ul class="nav-list">
                <li class="nav-item"><a href="index.php">Home</a></li>
                
                <li class="nav-item"><a href="login.php">Login</a></li>
            </ul>
        </div>
</div>
    <section id="home" class="carousel">
    <h1 class="title-main">Debra Events</h1>
    </section>
    <section class="body-main">
        <h1 class="title">Events</h1>
        <div class="event-section">
        <div class="event-container">
                <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <img src="<?php echo htmlspecialchars($baseImageUrl . $event['eventImage']); ?>" alt="Event Image" class="event-image">
                    <div class="event-details">
                        <h2 class="event-name"><?php echo htmlspecialchars($event['eventName']); ?></h2>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p class="event-date">Date: <?php echo htmlspecialchars($event['date']); ?></p>
                        <p class="event-location">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                        <p class="event-commission">Time: <?php echo htmlspecialchars($event['time']); ?></p>
                        <a href="client/viewEvent.php?eventID=<?php echo htmlspecialchars($event['eventID']); ?>" class="book-ticket-button">Book Ticket</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
            
    </section>
    
    <?php include("footer.php") ?>
</body>
</html>
