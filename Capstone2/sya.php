


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapbox Embedded Map with Sidebar</title>
    
    <link href='bootstap.min.css' rel='stylesheet' />
    <link href='mapbox-gl.css' rel='stylesheet' />
    <link href='nav.css' rel='stylesheet' />
    


</head>
<body>
    <div class="sidebar">
        <h1>Dashboard</h1>
        <a href="index.php">Home</a>
        <a href="record.html">Record</a>
        <a href="Graph">About</a>
        <a href="sya.php">Logout</a>
    </div>
    <div class="main-content">
        <header>
            <h1>Mapbox Embedded Map</h1>
        </header>
        <div id="map"></div>
    </div>
    <script src='slim.min.js'></script>
    <script src='proper.min.js'></script>
    <script src='bootstap.min.js'></script>
    <script src='mapbox-gl.js'></script>
 <script>
        // Mapbox access token (replace with your own)
        mapboxgl.accessToken = 'pk.eyJ1IjoiYXJ0LTEyMyIsImEiOiJjbHlibXJhMWwxZGYyMm1zZDc0Z3Y4bXNkIn0.iwKRejqSwTe-YDJr-8VoRQ';

        // Initialize the map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/art-123/clzxd8s7m000z01pd7d9agmji',
            center: [121.761755, 16.983545], // Longitude, Latitude
            zoom: 16,
            pitch: 60,
            bearing: -60
        });

        // Sample water level data (longitude, latitude, water level)
        const waterLevelData = [
            [121.761755, 16.983545, 5],
            [121.765211, 16.985626, 15],
            [121.768677, 16.989619, 40]
        ];

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

        // Add markers and lines to the map
        map.on('load', () => {
            const coordinates = waterLevelData.map(data => [data[0], data[1]]);

            // Add markers
            waterLevelData.forEach((data) => {
                const { color, level } = getColorAndLevel(data[2]);
                new mapboxgl.Marker({ color: color })
                    .setLngLat([data[0], data[1]])
                    .setPopup(new mapboxgl.Popup({ offset: 25 }).setText(`Water Level: ${data[2]} cm (${level})`)) // add pop-up with level
                    .addTo(map);
            });

            // Add line segments with different colors
            for (let i = 0; i < waterLevelData.length - 1; i++) {
                const color = getColorAndLevel(waterLevelData[i][2]).color;
                const segmentCoordinates = [
                    [waterLevelData[i][0], waterLevelData[i][1]],
                    [waterLevelData[i + 1][0], waterLevelData[i + 1][1]]
                ];

                map.addSource(`line-segment-${i}`, {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'LineString',
                            'coordinates': segmentCoordinates
                        }
                    }
                });

                map.addLayer({
                    'id': `line-segment-${i}`,
                    'type': 'line',
                    'source': `line-segment-${i}`,
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': color,
                        'line-width': 3
                    }
                });
            }

            // Determine the color for the line from the third to the fourth point
            const thirdLocationLevel = waterLevelData[2][2];
            const finalLineColor = getColorAndLevel(thirdLocationLevel).color;

            const greenLineCoordinates = [
                [121.768677, 16.989619],
                [121.771, 16.992]
            ];

            map.addSource('green-line', {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'LineString',
                        'coordinates': greenLineCoordinates
                    }
                }
            });

            map.addLayer({
                'id': 'green-line',
                'type': 'line',
                'source': 'green-line',
                'layout': {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                'paint': {
                    'line-color': finalLineColor,
                    'line-width': 3
                }
            });

            // Add terrain
            map.addSource('mapbox-dem', {
                'type': 'raster-dem',
                'url': 'mapbox://styles/mapbox/standard',
                'tileSize': 512,
                'maxzoom': 16
            });
            map.setTerrain({ 'source': 'mapbox-dem', 'exaggeration': 1.5 });
        });
    </script>
</body>
</html>
