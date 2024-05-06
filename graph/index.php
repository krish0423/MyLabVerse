<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hemogram Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<canvas id="hemogramChart" width="400" height="200"></canvas>

<script>
    // Fetch hemogram data using AJAX
    fetch('fetch_hemogram_data.php')
        .then(response => response.json())
        .then(data => {
            // Check if the response has data
            if (data.length > 0) {
                // Extract labels and values from the response
                const labels = Object.keys(data[0]);
                const values = data.map(item => Object.values(item));

                // Get the canvas element and create a bar chart
                const ctx = document.getElementById('hemogramChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Hemogram Data',
                            data: values[0], // Assuming the first row contains data
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        }],
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            } else {
                console.error('No hemogram data available.');
            }
        })
        .catch(error => {
            console.error('Error fetching hemogram data:', error);
        });
</script>

</body>
</html>
