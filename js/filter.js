function filterTable() {
    var input = document.getElementById('search').value.toLowerCase();
    var rows = document.querySelectorAll('#tenantTable tbody tr');
    rows.forEach(row => {
        var cells = row.getElementsByTagName('td');
        var match = false;
        for (var i = 0; i < cells.length; i++) {
            if (cells[i].textContent.toLowerCase().includes(input)) {
                match = true;
            }
        }
        row.style.display = match ? '' : 'none';
    });
}

// Function to sort the table based on the selected sort option
function sortTable() {
    var table = document.getElementById('tenantTable');
    var rows = Array.from(table.rows).slice(1);
    var sortOption = document.getElementById('sort-options').value;
    var index, direction;

    switch(sortOption) {
        case 'name-asc':
            index = 0;
            direction = 1;
            break;
        case 'landlord-asc':
            index = 1;
            direction = 1;
            break;
        case 'status-asc':
            index = 5;
            direction = 1;
            break;
        case 'balance-asc':
            index = 4;
            direction = 1;
            break;
    }

    rows.sort((a, b) => {
        var aText = a.cells[index].textContent.trim();
        var bText = b.cells[index].textContent.trim();
        
        if (direction === 1) {
            return aText.localeCompare(bText);
        } else {
            return bText.localeCompare(aText);
        }
    });

    rows.forEach(row => table.appendChild(row)); // Re-append rows in the new order
    filterTable(); // Re-filter rows after sorting
}