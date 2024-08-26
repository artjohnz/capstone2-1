<?php
// water_levels_table_with_search.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ako";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from water_levels table
$sql = "SELECT * FROM water_levels";
$result = $conn->query($sql);
$rows = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Levels Data</title>
    <link rel="stylesheet" type="text/css" href="nav.css">
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="record.css">
    <style>
       
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
    <div id="search-table-container">
        <div id="search-container">
            <input type="text" id="search-bar" onkeyup="searchTable()" placeholder="Search by ID...">
        </div>

        <div id="table-container" class="table-container">
            <table id="waterLevelsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Water Level</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['longitude'] ?></td>
                            <td><?= $row['latitude'] ?></td>
                            <td><?= $row['water_level'] ?></td>
                            <td><?= $row['timestamp'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pagination">
                <button id="prevBtn" onclick="changePage(-1)" disabled>Previous</button>
                <button id="nextBtn" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 - Capstone System
    </div>

    <script>
        const rows = <?= json_encode($rows) ?>;
        let currentPage = 1;
        const rowsPerPage = 10;

        function displayTablePage(page) {
            const tableBody = document.querySelector("#waterLevelsTable tbody");
            tableBody.innerHTML = "";

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedRows = rows.slice(start, end);

            paginatedRows.forEach(row => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.longitude}</td>
                    <td>${row.latitude}</td>
                    <td>${row.water_level}</td>
                    <td>${row.timestamp}</td>
                `;
                tableBody.appendChild(tr);
            });

            document.getElementById("prevBtn").disabled = page === 1;
            document.getElementById("nextBtn").disabled = end >= rows.length;
        }

        function changePage(direction) {
            currentPage += direction;
            displayTablePage(currentPage);
        }

        // Initial display
        displayTablePage(currentPage);

        function searchTable() {
            const input = document.getElementById("search-bar").value.toUpperCase();
            const table = document.getElementById("waterLevelsTable");
            const trs = table.getElementsByTagName("tr");

            for (let i = 1; i < trs.length; i++) {
                const td = trs[i].getElementsByTagName("td")[0];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    trs[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>
