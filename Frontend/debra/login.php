<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Login</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Form</h1>
     
        <form id="login-form" action="admin/authentication.php" method="POST" onsubmit="return validateForm()">
            <input type="text" id="username" name="username" placeholder="Username">
            <div class="error" id="username-error"></div>

            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="password-error"></div>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="registration.php">Click Here To Sign Up</a></p>
    </div>

    <script>
    function validateForm() {
        // Clear previous errors
        document.querySelectorAll('.error').forEach(error => error.innerHTML = '');

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        let isValid = true;

        // Validate Username
        if (!username) {
            document.getElementById('username-error').innerHTML = 'Username is required.';
            return false;
        }

        // Validate Password
        if (!password) {
            document.getElementById('password-error').innerHTML = 'Password is required.';
            return false;
        }

        

        

        return true;
    }
    </script>
</body>
</html>