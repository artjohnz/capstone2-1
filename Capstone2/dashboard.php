<?php
include './inc/connect.php';
 
// Fetch water level data
$sql = "SELECT longitude, latitude, water_level FROM water_levels ORDER BY timestamp DESC";
$result = $conn->query($sql);

$waterLevels = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $waterLevels[] = array(
            'longitude' => $row['longitude'],
            'latitude' => $row['latitude'],
            'water_level' => $row['water_level']
        );
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapbox Embedded Map with Sidebar</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="mapbox-gl.css">
    <link rel="stylesheet" type="text/css" href="nav.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
            /* Remove scroll from the entire page */
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
            /* Darker green on hover */
            color: #80FF80;
            /* Light green text on hover */
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

        }

        /* Container to center search bar and table */
        #search-table-container {
            margin-left: 250px;
            /* Adjust based on sidebar width */
            padding: 20px;
            overflow-y: auto;
            /* Scroll only in the main content area if necessary */
            height: calc(100vh - 40px);
            /* Adjust height based on padding */
            box-sizing: border-box;
        }

        #search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        #search-bar {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #table-container {
            text-align: center;
        }

        #waterLevelsTable {
            width: 99%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #waterLevelsTable th,
        #waterLevelsTable td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        #waterLevelsTable th {
            background-color: #4CAF50;
            color: white;
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
        <header>
            <h1>Irrigation Canal water level monitoring</h1>
        </header>
        <div id="map"></div>
    </div>

     <div class="footer">
        &copy; 2024 - Capstone System
    </div>
    <script src='slim.min.js'></script>
    <script src='proper.min.js'></script>
    <script src='bootstap.min.js'></script>
    <script src='mapbox-gl.js'></script>
    <script>
        // Mapbox access token (replace with your own)
        mapboxgl.accessToken = 'pk.eyJ1IjoiYXJ0LTEyMyIsImEiOiJjbHlibWc4NHkxZzlxMmpuMmplbnN0enIzIn0.DWjlrNgxsP6jlHKxvZZMwg';

        // Initialize the map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/art-123/clzxd8s7m000z01pd7d9agmji',
            center: [121.765211, 16.985626], // Longitude, Latitude
            zoom: 15.6,
            pitch: 60,
            bearing: 60,
        });

        // Fetch water level data from the PHP script
        fetch('get.php')
            .then(response => response.json())
            .then(waterLevelData => {
                // Add markers to the map
                waterLevelData.forEach((data) => {
                    const {
                        color,
                        level
                    } = getColorAndLevel(data.water_level);
                    new mapboxgl.Marker({
                            color: color
                        })
                        .setLngLat([data.longitude, data.latitude])
                        .setPopup(new mapboxgl.Popup({
                            offset: 25
                        }).setText(`Water Level: ${data.water_level} cm (${level})`)) // add pop-up with level
                        .addTo(map);
                });
            });

        // Define color scale for water levels
        const colorScale = ['#FF0000', '#FFFF00', '#00FF00']; // Red, Yellow, Green
        const levelThresholds = [10, 20, 40];

        // Function to get color and level based on water level
        const getColorAndLevel = (level) => {
            if (level >= 5 && level <= 10) {
                return {
                    color: colorScale[0],
                    level: 'Level 1'
                }; // Red
            } else if (level >= 11 && level <= 20) {
                return {
                    color: colorScale[1],
                    level: 'Level 2'
                }; // Yellow
            } else if (level >= 21 && level <= 40) {
                return {
                    color: colorScale[2],
                    level: 'Level 3'
                }; // Green
            }
        };
    </script>
</body>

</html>