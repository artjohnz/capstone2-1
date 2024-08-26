<?php
$servername = "localhost";
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password
$dbname = "Ako";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($waterLevels);

$conn->close();
?>
