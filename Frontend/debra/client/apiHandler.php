<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // API URLs
    $customerApiUrl = "https://localhost:7040/api/Customer";
    $lastInsertedCustomerApiUrl = "https://localhost:7040/api/Customer/lastInserted";
    $ticketApiUrl = "https://localhost:7040/api/Ticket/";
    $saleApiUrl = "https://localhost:7040/api/Sale";
    $commissionApiUrl = "https://localhost:7040/api/Commission/";

    // Extract customer details from POST
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $nic = $_POST['nic'];

    // Data for creating customer
    $customerData = array(
        'name' => $name,
        'email' => $email,
        'phoneNumber' => $phone,
        'nic' => $nic
    );

    // Initialize cURL session for creating customer
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $customerApiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($customerData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

    // Execute cURL session for creating customer
    $customerResponse = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        header("Location: index.php?status=error");
        exit();
    }

    // Close cURL session for creating customer
    curl_close($ch);

    // Decode customer response
    $customerData = json_decode($customerResponse, true);

    // Get last inserted customer ID
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $lastInsertedCustomerApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

    // Execute cURL session for getting last inserted customer ID
    $lastInsertedCustomerResponse = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        header("Location: index.php?status=error");
        exit();
    }

    // Close cURL session for getting last inserted customer ID
    curl_close($ch);

    // Decode last inserted customer response
    $lastInsertedCustomer = json_decode($lastInsertedCustomerResponse, true);
    $lastInsertedCustomerId = $lastInsertedCustomer['customerID'];

    // Process each ticket quantity from POST
    foreach ($_POST['quantity'] as $ticketID => $quantity) {

        // Fetch ticket details
        $ticketUrl = $ticketApiUrl . $ticketID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ticketUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

        // Execute cURL session for getting ticket details
        $ticketResponse = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            header("Location: index.php?status=error");
            exit();
        }

        // Close cURL session for getting ticket details
        curl_close($ch);

        // Decode ticket response
        $ticketData = json_decode($ticketResponse, true);

        // Update ticket sold count
        $ticketData['sold'] += $quantity;

        // Initialize cURL session for updating ticket
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ticketUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticketData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

        // Execute cURL session for updating ticket
        $updateTicketResponse = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            header("Location: index.php?status=error");
            exit();
        }

        // Close cURL session for updating ticket
        curl_close($ch);

        // Create sale records for each ticket purchased
        for ($i = 0; $i < $quantity; $i++) {
            // Prepare sale data for current ticket and quantity
            $saleData = array(
                'ticketID' => $ticketID,
                'customerID' => $lastInsertedCustomerId,
                'saleDate' => date('Y-m-d\TH:i:s'),
                'ticketNumber' => $ticketData['sold'] - $quantity + $i + 1 // Calculate ticket number based on current sold count
            );

            // Initialize cURL session for posting sale data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $saleApiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($saleData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

            // Execute cURL session for posting sale data
            $postSaleResponse = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                header("Location: index.php?status=error");
                exit();
            }

            // Close cURL session for posting sale data
            curl_close($ch);
        }
    }

    // Update commission for event
    $eventID = $_POST['eventID'];
    $commissionUrl = $commissionApiUrl . $eventID;

    // Fetch current commission details
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $commissionUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

    // Execute cURL session for fetching commission details
    $commissionResponse = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        header("Location: index.php?status=error");
        exit();
    }

    // Close cURL session for fetching commission details
    curl_close($ch);

    // Decode commission response
    $commissionData = json_decode($commissionResponse, true);

    // Calculate total amount based on ticket quantities and update total sales for commission
    $totalAmount = 0;
    foreach ($_POST['quantity'] as $ticketID => $quantity) {
        // Fetch ticket price and calculate amount for each ticket sold
        $ticketUrl = $ticketApiUrl . $ticketID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ticketUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
        $ticketResponse = curl_exec($ch);
        curl_close($ch);
        $ticketData = json_decode($ticketResponse, true);
        $totalAmount += $ticketData['price'] * $quantity;
    }

    // Update total sales for commission
    $commissionData['totalSales'] += $totalAmount;

    // Initialize cURL session for updating commission
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $commissionUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($commissionData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification

    // Execute cURL session for updating commission
    $updateCommissionResponse = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        header("Location: index.php?status=error");
        exit();
    }

    // Close cURL session for updating commission
    curl_close($ch);

    // Redirect or display success message after completing all operations
    header("Location: ticket.php?eventID=$eventID&customerID=$lastInsertedCustomerId");
    exit();
}
?>
