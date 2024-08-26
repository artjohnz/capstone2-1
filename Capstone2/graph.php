<?php
include './inc/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Level Graph</title>
    <link rel="stylesheet" type="text/css" href="nav.css">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevents any scrolling */
            height: 100vh; /* Full viewport height */
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: linear-gradient(240deg, #24b80d, #2c5c16, #22a645);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
        }

        .main-content {
            margin-left: 240px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 40px); /* Adjust height to make space for footer */
            padding: 20px;
            box-sizing: border-box;
        }

        .chart-container {
            width: 79%;
            max-width: 599px; /* Restricts the pie chart to a fixed maximum width */
            margin: 0 auto;
            text-align: center;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: linear-gradient(90deg, #09c619, #07cb16, #05d113);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
        }

        .sidebar h1 {
            color: #ffffff;
            margin-bottom: 30px;
            font-size: 36px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar li:hover {
            background-color: #004e00;
            color: #80FF80;
        }

        .footer {
    text-align: center;
    padding: 10px;
    background-color: #2c5c16;
    color: white;
    font-size: 14px;
    position: fixed;
    bottom: 0;
    width: calc(100% - 240px); /* Subtract the width of the sidebar */
    margin-left: 240px; /* Ensure it lines up correctly with the main content */
    display: flex;
    justify-content: center;
    align-items: center;
}

    </style>
</head>

<body>
    <div class="sidebar">
        <h1>Dashboard</h1>
        <ul>
            <li><a href="dashboard.php">Map Monitoring</a></li>
            <li><a href="record.php">Record</a></li>
            <li><a href="graph.php">Graph</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="chart-container">
            <h2>Water Level Percentage Distribution</h2>
            <canvas id="waterLevelChart"></canvas>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 - Capstone System
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        <?php
        // Query to get water levels by location
        $sql = "SELECT CONCAT(latitude, ', ', longitude) AS location, SUM(water_level) AS total_level FROM water_levels GROUP BY latitude, longitude";
        $result = $conn->query($sql);

        $locations = [];
        $percentages = [];
        $totalWaterLevel = 0;

        // Calculate the total water level
        while ($row = $result->fetch_assoc()) {
            $totalWaterLevel += $row['total_level'];
        }

        // Reset the result pointer and calculate percentages
        $result->data_seek(0);
        if ($totalWaterLevel > 0) {
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row['location'];
                $percentages[] = round(($row['total_level'] / $totalWaterLevel) * 100, 2);
            }
        }

        // Close connection
        $conn->close();
        ?>
        const ctx = document.getElementById('waterLevelChart').getContext('2d');
        const waterLevelChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($locations); ?>,
                datasets: [{
                    label: 'Water Level Percentage',
                    data: <?php echo json_encode($percentages); ?>,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(231, 76, 60, 0.2)',
                        'rgba(155, 89, 182, 0.2)',
                        'rgba(52, 152, 219, 0.2)',
                        'rgba(46, 204, 113, 0.2)',
                        'rgba(241, 196, 15, 0.2)',
                        'rgba(230, 126, 34, 0.2)',
                        'rgba(52, 73, 94, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(231, 76, 60, 1)',
                        'rgba(155, 89, 182, 1)',
                        'rgba(52, 152, 219, 1)',
                        'rgba(46, 204, 113, 1)',
                        'rgba(241, 196, 15, 1)',
                        'rgba(230, 126, 34, 1)',
                        'rgba(52, 73, 94, 1)'
                    ],
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Water Levels by Location (Percentage)'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
