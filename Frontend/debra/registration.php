<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Registration</title>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="registration-container">
        <h1>Register</h1>
       
        <form id="registration-form" action="admin/register.php" method="POST">
            <input type="text" id="partner-name" name="partnerName" placeholder="Partner Name" >
            <div class="error" id="partner-name-error"></div>

            <input type="email" id="email" name="email" placeholder="Email" >
            <div class="error" id="email-error"></div>

            <input type="tel" id="contact-number" name="contactNumber" placeholder="Contact Number" >
            <div class="error" id="contact-number-error"></div>

            <input type="text" id="address" name="address" placeholder="Address" >
            <div class="error" id="address-error"></div>

            <input type="password" id="password" name="password" placeholder="Password" >
            <div class="error" id="password-error"></div>

            <input type="password" id="confirm-password" name="confirmPassword" placeholder="Confirm Password" >
            <div class="error" id="confirm-password-error"></div>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script>
        document.getElementById('registration-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.error').forEach(error => error.innerHTML = '');

            const partnerName = document.getElementById('partner-name').value;
            const email = document.getElementById('email').value;
            const contactNumber = document.getElementById('contact-number').value;
            const address = document.getElementById('address').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            let isValid = validateForm(partnerName, email, contactNumber, address, password, confirmPassword);

            if (isValid) {
                // Submit the form
                this.submit();
            }
        });

        function validateForm(partnerName, email, contactNumber, address, password, confirmPassword) {
            let isValid = true;

            // Validate Partner Name
            if (!partnerName) {
                document.getElementById('partner-name-error').innerHTML = 'Partner Name is required.';
                isValid = false;
            }

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                document.getElementById('email-error').innerHTML = 'Email is required.';
                isValid = false;
            } else if (!emailPattern.test(email)) {
                document.getElementById('email-error').innerHTML = 'Invalid email format.';
                isValid = false;
            }

            // Validate Contact Number
            const contactNumberPattern = /^\d{10}$/;
            if (!contactNumber) {
                document.getElementById('contact-number-error').innerHTML = 'Contact Number is required.';
                isValid = false;
            } else if (!contactNumberPattern.test(contactNumber)) {
                document.getElementById('contact-number-error').innerHTML = 'Contact Number must be 10 digits.';
                isValid = false;
            }

            // Validate Username
            if (!address) {
                document.getElementById('address-error').innerHTML = 'Address is required.';
                isValid = false;
            }

            // Validate Password
            if (!password) {
                document.getElementById('password-error').innerHTML = 'Password is required.';
                isValid = false;
            }

            // Validate Confirm Password
            if (!confirmPassword) {
                document.getElementById('confirm-password-error').innerHTML = 'Confirm Password is required.';
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById('confirm-password-error').innerHTML = 'Passwords do not match.';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>
