document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('.menu li a');
    const sections = document.querySelectorAll('.content-section');

    menuLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            // Hide all sections
            sections.forEach(section => section.style.display = 'none');

            // Remove active class from all links
            menuLinks.forEach(link => link.classList.remove('active'));

            // Show the clicked section
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).style.display = 'block';

            // Add active class to the clicked link
            this.classList.add('active');
        });
    });

    // Initialize the chart
    const ctx = document.getElementById('sales-chart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Tickets Sold',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Handle event form submission
    document.getElementById('event-form').addEventListener('submit', function(event) {
        event.preventDefault();
        // Handle form submission logic here
        alert('Event Created!');
        this.reset();
    });
});

