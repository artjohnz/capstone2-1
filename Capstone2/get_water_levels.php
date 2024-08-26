<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "water_levels_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch water level data
$sql = "SELECT longitude, latitude, water_level FROM water_levels ORDER BY id ASC";
$result = $conn->query($sql);

$waterLevelData = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $waterLevelData[] = [
            'longitude' => $row['longitude'],
            'latitude' => $row['latitude'],
            'water_level' => $row['water_level']
        ];
    }
}

// Return JSON encoded data
header('Content-Type: application/json');
echo json_encode($waterLevelData);

$conn->close();
?>
