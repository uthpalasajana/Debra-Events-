<?php
// Get eventID, ticket quantities, and customer details from POST
$eventID = isset($_POST['eventID']) ? $_POST['eventID'] : die('Event ID not specified.');
$quantities = isset($_POST['quantity']) ? $_POST['quantity'] : die('Ticket quantities not specified.');
$name = isset($_POST['name']) ? $_POST['name'] : '';

$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$nic = isset($_POST['nic']) ? $_POST['nic'] : '';

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
    <title>Payment Details</title>
    <link rel="stylesheet" href="../assets/css/stylesheet1.css">
    <script>
        function formatCardNumber(event) {
            var input = event.target;
            var value = input.value.replace(/\D/g, '').substring(0, 16);
            var formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            input.value = formattedValue;
        }

        function validateForm() {
            var cardType = document.getElementById('cardType').value;
            var cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '').trim();
            var expDate = document.getElementById('expDate').value.trim();
            var cvv = document.getElementById('cvv').value.trim();
            var errorMsg = '';

            // Validate card number
            if (cardNumber === '' || !/^\d{16}$/.test(cardNumber)) {
                errorMsg += 'Invalid card number. Must be 16 digits.<br>';
            }

            // Validate expiration date
            else if (expDate === '' || !/^\d{2}\/\d{2}$/.test(expDate)) {
                errorMsg += 'Must be in MM/YY format.<br>';
            } 

            // Validate CVV
            else if (cvv === '' || !/^\d{3,4}$/.test(cvv)) {
                errorMsg += 'Invalid CVV. Must be 3 or 4 digits.<br>';
            }

            if (errorMsg !== '') {
                document.getElementById('error-message').innerHTML = errorMsg;
                return false;
            }
            return true;
        }

        window.addEventListener('DOMContentLoaded', function() {
            var cardNumberInput = document.getElementById('cardNumber');
            cardNumberInput.addEventListener('input', formatCardNumber);
        });
    </script>
</head>

<body>
<?php include("../header.php") ?> 
    <section class="payment-section">
        <div class="payment-container">
            <h1 class="payment-title">Payment</h1>
            <div class="billing-details">
                <h3>Payments</h3>
                <form action="apiHandler.php" method="POST" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="cardType">Card Type</label>
                        <select id="cardType" name="cardType" required>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                            <option value="AmEx">American Express</option>
                            <option value="Discover">Discover</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" >
                    </div>
                    <div class="form-group">
                        <label for="expDate">Expiration Date</label>
                        <input type="text" id="expDate" name="expDate" placeholder="MM/YY" d>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" >
                    </div>
                    <div id="error-message" style="color: red; margin-bottom: 20px; margin-top: 20px; text-align: center;"></div>
                    <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">
                    <?php foreach ($quantities as $ticketID => $quantity) : ?>
                        <input type="hidden" name="quantity[<?php echo htmlspecialchars($ticketID); ?>]" value="<?php echo htmlspecialchars($quantity); ?>">
                    <?php endforeach; ?>
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <input type="hidden" name="nic" value="<?php echo htmlspecialchars($nic); ?>">
                    <div class="proceed-button-container">
                    <button type="submit" class="proceed-button">Complete Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php include("../footer.php") ?> 
</body>

</html>
