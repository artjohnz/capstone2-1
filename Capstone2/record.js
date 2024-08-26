function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search-bar");
    filter = input.value.toUpperCase(); // Get the search input
    table = document.getElementById("waterLevelsTable");
    tr = table.getElementsByTagName("tr");

    if (filter === "") {
        // If search input is empty, show all rows
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = ""; // Show the row
        }
        return; // Exit the function early
    }

    // Otherwise, perform search
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]; // Target the ID column (first column)
        if (td) {
            txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none"; // Show or hide row
        }
    }
}