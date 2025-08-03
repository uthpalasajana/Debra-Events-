<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Add New Event</title>  <!--methana wenas kalaa-->
    <link rel="stylesheet" href="../assets/css/adminstyle1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add custom styles for demonstration */
        .artist-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .artist-table th, .artist-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>
        <div class="content">
            <div id="create-event" class="content-section">
                <h1 style="text-align: center; font-size: 40px;">Add New Event</h1> <!--methana wenas kalaa-->
                <form id="event-form" action="addEvent.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="col">
                            <label for="event-name">Event Name</label>
                            <input type="text" id="event-name" name="eventName" placeholder="Event Name" required>
                        </div>
                        <div class="col">
                            <label for="event-venue">Venue</label>
                            <input type="text" id="event-venue" name="eventVenue" placeholder="Venue" required>
                        </div> 
                    </div>
                    <div class="form-group">
                        <div class="col">
                            <label for="event-date">Date</label>
                            <input type="date" id="event-date" name="eventDate" required>
                        </div>
                        <div class="col">
                            <label for="event-time">Time</label>
                            <input type="time" id="event-time" name="eventTime" required>
                        </div>   
                    </div>
                    <div class="form-group">
                        <div class="col">
                            <label for="event-description">Description</label>
                            <textarea id="event-description" name="eventDescription" rows="4" placeholder="Event Description"></textarea>
                        </div>
                        <div class="col">
                            <label for="event-image">Event Image</label>
                            <input type="file" id="event-image" name="eventImage" accept="image/*" required>
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="col">
                            <label for="partner-id">Select Partner</label>
                            <select id="partner-id" name="partnerID" required>
                                <!-- Partners will be dynamically added here -->
                                <?php
                                // PHP code to fetch partners and generate options
                                $partnerApiUrl = 'https://localhost:7040/api/Partner';

                                // Initialize cURL session
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $partnerApiUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

                                // Execute cURL session
                                $response = curl_exec($ch);

                                // Check for cURL errors
                                if(curl_errno($ch)) {
                                    echo 'Error:' . curl_error($ch);
                                }

                                // Close cURL session
                                curl_close($ch);

                                // Decode JSON response
                                $partners = json_decode($response, true);

                                // Output options for select dropdown
                                if ($partners !== null && !empty($partners)) {
                                    foreach ($partners as $partner) {
                                        echo '<option value="' . $partner['partnerID'] . '">' . $partner['name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No partners available</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="commission-rate">Commission Rate (%)</label>
                            <input type="number" id="commission-rate" name="commissionRate" placeholder="Commission Rate" min="0" max="100" step="0.1" required>
                        </div>
                    </div>

                    <div class="ticket-sec">
                        <div class="title-sec">
                            <label for="event-tickets">Ticket Category</label>
                        </div>
                        <div class="ticket-cat">
                            <div class="sec-one">
                                <input type="text" id="ticket-category" placeholder="Ticket Category">
                            </div>
                            <div class="sec-one">
                                <input type="text" id="ticket-price" placeholder="Ticket Price">
                            </div>
                            <div class="sec-one">
                                <input type="number" id="ticket-quantity" placeholder="Quantity">
                            </div>
                            <button type="button" id="add-category-btn"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="table-sec">
                            <table id="category-table" class="artist-table" style="margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Ticket Price</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ticket categories will be added dynamically here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="button-class" id="btn-submit">Create Event</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('event-form');
            const ticketTable = document.getElementById('category-table').getElementsByTagName('tbody')[0];
            const addCategoryBtn = document.getElementById('add-category-btn');
            let ticketId = 1;

            function addHiddenInput(name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            }

            // Handle add ticket category button
            addCategoryBtn.addEventListener('click', function() {
                const ticketCategoryInput = document.getElementById('ticket-category');
                const ticketPriceInput = document.getElementById('ticket-price');
                const ticketQuantityInput = document.getElementById('ticket-quantity');
                const ticketCategory = ticketCategoryInput.value.trim();
                const ticketPrice = ticketPriceInput.value.trim();
                const ticketQuantity = ticketQuantityInput.value.trim();

                if (ticketCategory === '' || ticketPrice === '' || ticketQuantity === '') {
                    alert('Please enter ticket category, price, and quantity.');
                    return;
                }

                // Add category to table
                const newRow = ticketTable.insertRow();
                newRow.dataset.ticketId = ticketId;
                const cell1 = newRow.insertCell(0);
                const cell2 = newRow.insertCell(1);
                const cell3 = newRow.insertCell(2);
                const cell4 = newRow.insertCell(3);
                const cell5 = newRow.insertCell(4);

                cell1.textContent = ticketId++;
                cell2.textContent = ticketCategory;
                cell3.textContent = ticketPrice;
                cell4.textContent = ticketQuantity;
                cell5.innerHTML = '<button type="button" class="remove-category-btn">Remove</button>';

                // Add hidden inputs
                addHiddenInput(`ticketCategory[${ticketId}]`, ticketCategory);
                addHiddenInput(`ticketPrice[${ticketId}]`, ticketPrice);
                addHiddenInput(`ticketQuantity[${ticketId}]`, ticketQuantity);

                // Clear input fields
                ticketCategoryInput.value = '';
                ticketPriceInput.value = '';
                ticketQuantityInput.value = '';
            });

            // Handle remove ticket category button
            ticketTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-category-btn')) {
                    const row = event.target.closest('tr');
                    const ticketId = row.dataset.ticketId;
                    // Remove hidden inputs
                    const inputs = form.querySelectorAll(`input[name^="ticketCategory[${ticketId}]"], input[name^="ticketPrice[${ticketId}]"], input[name^="ticketQuantity[${ticketId}]"]`);
                    inputs.forEach(input => input.remove());
                    ticketTable.deleteRow(row.rowIndex - 1); // Adjust for thead row
                }
            });

            // Form submission handler (validate if needed)
            form.addEventListener('submit', function(event) {
                // Validate form fields if necessary
                // event.preventDefault();
                // Add further validation logic here
            });
        });
    </script>
</body>
</html>
