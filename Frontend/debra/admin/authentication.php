<?php
session_start(); // Start session for storing user email

// Function to check password via API
function checkPassword($url, $email, $password) {
    $data = array('Email' => $email, 'Password' => $password);
    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    ));

    // Disable SSL verification for local testing, adjust for production
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return array(
        'response' => json_decode($result, true),
        'http_code' => $http_code
    );
}

// Get the posted email and password from the form
$email = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Check user authentication
$userUrl = 'https://localhost:7040/api/User/checkpassword';
$userResult = checkPassword($userUrl, $email, $password);

if ($userResult['http_code'] == 200) {
    // User authenticated successfully, store user email in session
    $_SESSION['user']['email'] = $email;

    // Redirect to dashboard or appropriate page
    header('Location: viewEvents.php');
    exit;
} elseif ($userResult['http_code'] == 401) {
    // User authentication failed, redirect to login with error status
    header('Location: ../login.php?status=error');
    exit;
}

// Check partner authentication if user authentication failed
$partnerUrl = 'https://localhost:7040/api/Partner/checkpassword';
$partnerResult = checkPassword($partnerUrl, $email, $password);

if ($partnerResult['http_code'] == 200) {
     // User authenticated successfully, store user email in session
    $_SESSION['user']['email'] = $email;
    header('Location: ../partner/partner.php');
    exit;
} elseif ($partnerResult['http_code'] == 401) {
    // Partner authentication failed, redirect to login with error status
    header('Location: ../login.php?status=error');
    exit;
}

// If neither user nor partner exists, redirect to login with error status
header('Location: ../login.php?status=error');
exit;
?>
