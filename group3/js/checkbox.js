// Function to handle deletion of selected employees
function deleteSelected() {
    var checkboxes = document.querySelectorAll('input[name="selectedEmployees[]"]:checked');
    var employeeIDs = [];
    checkboxes.forEach(function (checkbox) {
        employeeIDs.push(checkbox.value);
    });

    if (employeeIDs.length === 0) {
        alert('Please select at least one employee to delete.');
    } else {
        var confirmation = confirm('Are you sure you want to delete the selected employees?');
        if (confirmation) {
            // Redirect to delete script with selected employee IDs
            window.location.href = 'crud/deleteemp.php?ids=' + employeeIDs.join(',');
        }
    }
}
