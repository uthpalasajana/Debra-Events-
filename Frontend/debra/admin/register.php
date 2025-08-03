<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs (already done in JavaScript)

    // Retrieve form data
    $partnerName = $_POST['partnerName'] ?? '';
    $email = $_POST['email'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare data array for API request
    $partnerData = [
        'partnerID' => 0,
        'name' => $partnerName,
        'contactInfo' => $contactNumber,
        'address' => $address,
        'email' => $email,
        'password' => $password,
        'registeredDate' => date('c')  // Current date and time in ISO 8601 format
    ];

    // Endpoint URL for partner registration
    $partnerApiUrl = "https://localhost:7040/api/Partner";

    // Initialize cURL session for partner registration
    $ch_partner = curl_init();
    curl_setopt($ch_partner, CURLOPT_URL, $partnerApiUrl);
    curl_setopt($ch_partner, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_partner, CURLOPT_POST, true);
    curl_setopt($ch_partner, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch_partner, CURLOPT_POSTFIELDS, json_encode($partnerData));
    curl_setopt($ch_partner, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
    curl_setopt($ch_partner, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // Execute cURL session for partner registration
    $response_partner = curl_exec($ch_partner);

    // Check for cURL errors for partner registration
    if (curl_errno($ch_partner)) {
        echo 'Error:' . curl_error($ch_partner);
        exit();
    }

    // Check HTTP response code
    $httpCode = curl_getinfo($ch_partner, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        echo 'HTTP Error Code: ' . $httpCode;
        exit();
    }

    // Close cURL session for partner registration
    curl_close($ch_partner);

    // Redirect to login page after successful registration
    header("Location: ../login.php");
    exit();
}
?>
