<?php
// Database configuration
$servername = "localhost"; // Server name (usually "localhost" for local development)
$username = "root";        // MySQL username (default is "root")
$password = "";            // MySQL password (default is an empty string for "root" on local setups)
$database = "ako"; // Name of the database you created for your login system

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// No output if the connection is successful
?>
