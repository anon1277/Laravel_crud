      
// Open the modal for adding/editing an employee
$('#employees-table').on('click', '.edit-btn', function() {
    var employeeId = $(this).data('id');
    $('#employeeId').val(employeeId);

    if (employeeId) {
        // Editing an existing employee
        $('#employeeModalLabel').text('Edit Employee');
        $('#saveBtn').text('Update');
    } else {
        // Adding a new employee
        $('#employeeModalLabel').text('Add Employee');
        $('#saveBtn').text('Save');
    }

    $.ajax({
        url: "{{ route('employees.show', ':id') }}".replace(':id', employeeId),
        type: 'GET',
        success: function(response) {
            var employee = response.employee;
            $('#first_name').val(employee.first_name);
            $('#last_name').val(employee.last_name);
            $('#email').val(employee.email);
            $('#password').val('');
        }
    });

    $('#employeeModal').modal('show');
});

var csrf = document.querySelector('meta[name="csrf-token"]').content;

// Handle form submission for adding/editing an employee
$('#employeeForm').submit(function(e) {
    e.preventDefault();

    var employeeId = $('#employeeId').val();
    var url = '';
    var method = '';

    if (employeeId) {
        // Editing an existing employee
        url = "{{ route('employees.update', ':id') }}".replace(':id', employeeId);
        method = 'PUT';
        Swal.fire({
            icon: 'success',
            title: 'Employee Updated',
            text: 'Data updated successfully!',
        });
    } else {
        // Adding a new employee
        url = "{{ route('employees.store') }}";
        method = 'POST';
        Swal.fire({
            icon: 'success',
            title: 'Employee Added',
            text: 'Employee added successfully!',
        });
    }

    var formData = $(this).serialize();
    formData += '&_token=' + encodeURIComponent(csrf);

    $.ajax({
        url: url,
        type: method,
        data: formData,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
        },
        success: function(response) {
            if (response.success) {
                $('#employeeModal').modal('hide');
                table.ajax.reload();
                resetForm();
            }
        }
    });

    // Close the form
    $('#employeeModal').modal('hide');
    table.ajax.reload();
});

// Reset the form and clear input fields
function resetForm() {
    $('#employeeId').val('');
    $('#first_name').val('');
    $('#last_name').val('');
    $('#email').val('');
    $('#password').val('');
    $('#employeeModalLabel').text('Add Employee');
    $('#saveBtn').text('Save');
}