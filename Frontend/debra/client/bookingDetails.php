<?php
// Get eventID, ticket quantities from POST
$eventID = isset($_POST['eventID']) ? $_POST['eventID'] : die('Event ID not specified.');
$quantities = isset($_POST['quantity']) ? $_POST['quantity'] : die('Ticket quantities not specified.');

// API URLs
$eventUrl = "https://localhost:7040/api/Event/$eventID";
$ticketsUrl = "https://localhost:7040/api/Event/ticket/$eventID";

// Initialize cURL session for event details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $eventUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$eventResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session for ticket details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ticketsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$ticketsResponse = curl_exec($ch);
curl_close($ch);

// Decode JSON responses to PHP arrays
$event = json_decode($eventResponse, true);
$tickets = json_decode($ticketsResponse, true);

// Check if decoding was successful
if ($event === null || $tickets === null) {
    die('Error decoding JSON');
}

// Calculate total amount
$totalAmount = 0;
foreach ($quantities as $ticketID => $quantity) {
    foreach ($tickets as $ticket) {
        if ($ticket['ticketID'] == $ticketID) {
            $totalAmount += $ticket['price'] * $quantity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link rel="stylesheet" href="../assets/css/stylesheet1.css">
    <script>
        function validateForm() {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var nic = document.getElementById('nic').value.trim();
            
            var errorMsg = '';

            if (name === '' || email === '' || phone === '' || nic === '') {
                errorMsg += 'Fill in all the fields.<br>';
            }else if (!/\S+@\S+\.\S+/.test(email)) {
                errorMsg += 'Invalid email format.<br>';
            }
            


            if (errorMsg !== '') {
                document.getElementById('error-message').innerHTML = errorMsg;
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
<?php include("../header.php") ?> 
    <section class="payment-section">
        <div class="payment-container">
            <h1 class="payment-title">Bookings</h1>
            <div class="billing-details">
                <h3>Billing Details</h3>
                <form action="paymentDetails.php" method="POST" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone No</label>
                        <input type="text" id="phone" name="phone" value="" placeholder="Phone No">
                    </div>
                    <div class="form-group">
                        <label for="nic">NIC</label>
                        <input type="text" id="nic" name="nic" value="" placeholder="NIC Number">
                    </div>
                    <h3 class="booking-subtitle">Booking Summary</h3>
                    <?php foreach ($quantities as $ticketID => $quantity) : ?>
                        <?php if ($quantity > 0) : ?>
                            <?php foreach ($tickets as $ticket) : ?>
                                <?php if ($ticket['ticketID'] == $ticketID) : ?>
                                    <div class="booking-item">
                                        <p><?php echo htmlspecialchars($quantity); ?> x <?php echo htmlspecialchars($ticket['ticketType']); ?> Ticket(s)</p>
                                        <p><?php echo number_format($ticket['price'], 2); ?> LKR each</p>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="subtotal">
                        <p>Sub Total</p>
                        <p><?php echo number_format($totalAmount, 2); ?> LKR</p>
                    </div>
                    <div class="total">
                        <p>Total</p>
                        <p><?php echo number_format($totalAmount, 2); ?> LKR</p>
                    </div>
                    <div id="error-message" style="color: red; margin-bottom: 20px; margin-top: 20px; text-align: center;"></div>

                    <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">
                    <?php foreach ($quantities as $ticketID => $quantity) : ?>
                        <input type="hidden" name="quantity[<?php echo htmlspecialchars($ticketID); ?>]" value="<?php echo htmlspecialchars($quantity); ?>">
                    <?php endforeach; ?>

                    <div class="proceed-button-container">
                    <button type="submit" class="proceed-button">Proceed to Payment</button>
                    </div>
                       
                </form>
            </div>
        </div>
    </section>
    <?php include("../footer.php") ?> 
</body>

</html>
