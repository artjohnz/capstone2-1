<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapbox Embedded Map with Sidebar</title>
    
    <link href='bootstrap.min.css' rel='stylesheet' />
    <link href='mapbox-gl.css' rel='stylesheet' />
    <link href='nav.css' rel='stylesheet' />
<style>
    body {
        font-family: "Lato", sans-serif;
        margin: 0;
        padding: 0;
        background-color: #111;
        overflow: hidden;
        display: flex;
    }
    
    .sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #004e00, #008000); /* Gradient background */
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        color: white;
        overflow-y: auto;
    }
    
    .sidebar h1 {
        color: #ffffff;
        margin-bottom: 30px;
        font-size: 36px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
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
    
    .sidebar ul li a:hover {
        background-color: #004e00; /* Darker green on hover */
        color: #80FF80; /* Light green text on hover */
    }
    
    .main-content {
        margin-left: 250px; /* Sidebar width */
        background: linear-gradient(to bottom, #008000, #004e00); /* Gradient background */
        height: 100vh; /* Full viewport height */
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
    }
    
    header {
        background-color: #6e8efb; /* Light green */
        color: white;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
        flex-shrink: 0; /* Prevent header from shrinking */
        font-size: 32px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }
    
    #map {
        flex-grow: 1; /* Make the map take up remaining space */
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
    }
    
    </style>

</head>
<body>
    <div class="sidebar">
        <h1>Dashboard</h1>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <header>
            <h1>Mapbox Embedded Map</h1>
        </header>
        <div id="map"></div>
    </div>

    <script src='slim.min.js'></script>
    <script src='proper.min.js'></script>
    <script src='bootstrap.min.js'></script>
    <script src='mapbox-gl.js'></script>
    <script>
        // Mapbox access token (replace with your own)
        mapboxgl.accessToken = 'pk.eyJ1IjoiYXJ0LTEyMyIsImEiOiJjbHlibWc4NHkxZzlxMmpuMmplbnN0enIzIn0.DWjlrNgxsP6jlHKxvZZMwg';

        // Initialize the map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/art-123/clzxd8s7m000z01pd7d9agmji',
            center: [121.765211, 16.985626], // Longitude, Latitude
            zoom: 15,
            pitch: 69,
            bearing: -60,
        });

        // Fetch water level data from the PHP script
        fetch('get.php')
            .then(response => response.json())
            .then(waterLevelData => {
                // Add markers to the map
                waterLevelData.forEach((data) => {
                    const { color, level } = getColorAndLevel(data.water_level);
                    new mapboxgl.Marker({ color: color })
                        .setLngLat([data.longitude, data.latitude])
                        .setPopup(new mapboxgl.Popup({ offset: 25 }).setText(`Water Level: ${data.water_level} cm (${level})`)) // add pop-up with level
                        .addTo(map);
                });
            });

        // Define color scale for water levels
        const colorScale = ['#FF0000', '#FFFF00', '#00FF00']; // Red, Yellow, Green
        const levelThresholds = [10, 20, 40];

        // Function to get color and level based on water level
        const getColorAndLevel = (level) => {
            if (level >= 5 && level <= 10) {
                return { color: colorScale[0], level: 'Level 1' }; // Red
            } else if (level >= 11 && level <= 20) {
                return { color: colorScale[1], level: 'Level 2' }; // Yellow
            } else if (level >= 21 && level <= 40) {
                return { color: colorScale[2], level: 'Level 3' }; // Green
            }
        };
    </script>
</body>
</html>
