<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded
    if ($_FILES['eventImage']['size'] > 0) {
        // Endpoint URL for uploading image
        $uploadImageUrl = "https://localhost:7040/api/Event/upload";

        // Initialize cURL session for image upload
        $ch_upload = curl_init();
        curl_setopt($ch_upload, CURLOPT_URL, $uploadImageUrl);
        curl_setopt($ch_upload, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_upload, CURLOPT_POST, true);
        curl_setopt($ch_upload, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
        curl_setopt($ch_upload, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_upload, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($_FILES['eventImage']['tmp_name'], $_FILES['eventImage']['type'], $_FILES['eventImage']['name'])
        ]);

        // Execute cURL session for image upload
        $response_upload = curl_exec($ch_upload);

        // Check for cURL errors for image upload
        if (curl_errno($ch_upload)) {
            echo 'Error:' . curl_error($ch_upload);
            exit();
        }

        // Close cURL session for image upload
        curl_close($ch_upload);

        // Decode JSON response
        $uploadResponse = json_decode($response_upload, true);

        // Check if image upload was successful
        if ($uploadResponse) {
            $imageUrl = 'images/' . $_FILES['eventImage']['name'];

            // Proceed to create event with the uploaded image URL
            createEvent($imageUrl);
        } else {
            // Handle error case for image upload
            echo "Failed to upload image. Please try again.";
            exit();
        }
    } else {
        // Handle case where no file is uploaded
        echo "No image file uploaded.";
        exit();
    }
}

function createEvent($imageUrl) {
    // Retrieve other form data
    $eventName = $_POST['eventName'] ?? '';
    $eventVenue = $_POST['eventVenue'] ?? '';
    $eventDate = $_POST['eventDate'] ?? '';
    $eventTime = $_POST['eventTime'] ?? '';
    $eventDescription = $_POST['eventDescription'] ?? '';
    $commissionRate = $_POST['commissionRate'] ?? '';
    $partnerID = $_POST['partnerID'] ?? '';

    // Prepare event data including the uploaded image URL
    $eventData = [
        'partnerID' => $partnerID,
        'eventName' => $eventName,
        'description' => $eventDescription,
        'date' => $eventDate,
        'time' => $eventTime,
        'location' => $eventVenue,
        'createdDate' => date('c'),  // Current date and time in ISO 8601 format
        'eventImage' => $imageUrl
    ];

    // Endpoint URL for creating event
    $eventApiUrl = "https://localhost:7040/api/Event";

    // Initialize cURL session for event creation
    $ch_event = curl_init();
    curl_setopt($ch_event, CURLOPT_URL, $eventApiUrl);
    curl_setopt($ch_event, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_event, CURLOPT_POST, true);
    curl_setopt($ch_event, CURLOPT_POSTFIELDS, http_build_query($eventData));
    curl_setopt($ch_event, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
    curl_setopt($ch_event, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // Execute cURL session for event creation
    $response_event = curl_exec($ch_event);

    // Check for cURL errors for event creation
    if (curl_errno($ch_event)) {
        echo 'Error:' . curl_error($ch_event);
        exit();
    }

    // Close cURL session for event creation
    curl_close($ch_event);

    // Decode JSON response
    $eventResponse = json_decode($response_event, true);

    // Endpoint URL to fetch last event ID
    $lastEventIdUrl = "https://localhost:7040/api/Event/lastEventID";

    // Initialize cURL session for fetching last event ID
    $ch_last_event = curl_init();
    curl_setopt($ch_last_event, CURLOPT_URL, $lastEventIdUrl);
    curl_setopt($ch_last_event, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_last_event, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
    curl_setopt($ch_last_event, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // Execute cURL session for fetching last event ID
    $response_last_event = curl_exec($ch_last_event);

    // Check for cURL errors
    if (curl_errno($ch_last_event)) {
        echo 'Error:' . curl_error($ch_last_event);
        return false;
    }

    // Close cURL session for fetching last event ID
    curl_close($ch_last_event);

    // Decode JSON response
    $lastEventResponse = json_decode($response_last_event, true);
    $eventID = htmlspecialchars($lastEventResponse);

    // Check if event creation was successful
    if ($eventID) {

        // Handle ticket categories (if applicable)
        if (isset($_POST['ticketCategory']) && isset($_POST['ticketPrice']) && isset($_POST['ticketQuantity'])) {
            $ticketCategories = $_POST['ticketCategory'];
            $ticketPrices = $_POST['ticketPrice'];
            $ticketQuantities = $_POST['ticketQuantity'];
        
            // Loop through each ticket category and post to API
            foreach ($ticketCategories as $index => $ticketCategory) {
                $ticketData = [
                    'eventID' => $eventID,
                    'ticketType' => $ticketCategory,
                    'price' => $ticketPrices[$index],
                    'quantity' => $ticketQuantities[$index],
                    'sold' => 0  // Initialize as 0, can be updated later
                ];
        
                // Endpoint URL for creating ticket
                $ticketApiUrl = "https://localhost:7040/api/Ticket";
        
                // Initialize cURL session for ticket creation
                $ch_ticket = curl_init();
                curl_setopt($ch_ticket, CURLOPT_URL, $ticketApiUrl);
                curl_setopt($ch_ticket, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_ticket, CURLOPT_POST, true);
                curl_setopt($ch_ticket, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Set JSON headers
                curl_setopt($ch_ticket, CURLOPT_POSTFIELDS, json_encode($ticketData)); // Send data as JSON
                curl_setopt($ch_ticket, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
                curl_setopt($ch_ticket, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
        
                // Execute cURL session for ticket creation
                $response_ticket = curl_exec($ch_ticket);
        
                // Check for cURL errors for ticket creation
                if (curl_errno($ch_ticket)) {
                    echo 'Error:' . curl_error($ch_ticket);
                    exit();
                }
        
                // Close cURL session for ticket creation
                curl_close($ch_ticket);
        
                // Handle ticket creation response if needed
            }
        }

        // Handle commission data (if applicable)
        $commissionData = [
            'commissionID' => 0, // Assuming the ID is auto-generated by the backend
            'eventID' => $eventID,
            'commissionRate' => floatval($commissionRate),
            'totalSales' => 0
        ];

        // Endpoint URL for creating commission
        $commissionApiUrl = "https://localhost:7040/api/Commission";

        // Initialize cURL session for commission creation
        $ch_commission = curl_init();
        curl_setopt($ch_commission, CURLOPT_URL, $commissionApiUrl);
        curl_setopt($ch_commission, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_commission, CURLOPT_POST, true);
        curl_setopt($ch_commission, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Set JSON headers
        curl_setopt($ch_commission, CURLOPT_POSTFIELDS, json_encode($commissionData)); // Send data as JSON
        curl_setopt($ch_commission, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
        curl_setopt($ch_commission, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

        // Execute cURL session for commission creation
        $response_commission = curl_exec($ch_commission);

        // Check for cURL errors for commission creation
        if (curl_errno($ch_commission)) {
            echo 'Error:' . curl_error($ch_commission);
            exit();
        }

        // Close cURL session for commission creation
        curl_close($ch_commission);

        // Decode JSON response
        

        // Redirect or show success message
        header("Location: viewEvents.php");
        exit();
    } else {
        // Handle error case for event creation
        echo "Failed to create event. Please try again.";
    }
}
?>
