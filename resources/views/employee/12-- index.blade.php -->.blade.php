<!-- index.blade.php -->

@extends('layouts.layout')


@section('content')

    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="container">

        <div class="card">
            <div class="card-header">
                Employee List
                <a href="{{ route('logout') }}">Logout</a>
                <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                    data-target="#employeeModal">Add Employee</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="employees-table">
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Add/Edit Employee</h5>
                    <button type="button" required class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="employeeForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="employeeId">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" required placeholder="Enter Your  Name"
                                class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                id="first_name" value="{{ old('first_name') }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" required placeholder="Enter Last Name"
                                class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                id="last_name" value="{{ old('last_name') }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" required placeholder="Enter  Email Address"
                                class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" required placeholder="Enter Password"
                                class="form-control @error('password') is-invalid @enderror" name="password" id="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Include jQuery Validation plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- Include Bootstrap library -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Include SweetAlert 2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
    <!-- Include SweetAlert 2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>






    <!-- Bootstrap library -->

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        $(document).ready(function() {
            // Initialize form validation
            var employeeForm = $('#employeeForm');
            employeeForm.validate({
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    first_name: {
                        required: 'Please enter your first name'
                    },
                    last_name: {
                        required: 'Please enter your last name'
                    },
                    email: {
                        required: 'Please enter your email address',
                        email: 'Please enter a valid email address'
                    },
                    password: {
                        required: 'Please enter a password',
                        minlength: 'Password must be at least 6 characters long'
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    form.submit(); // Form submission is allowed if it passes validation
                }
            });

            // Clear validation messages when the form is closed
            var closeButton = employeeForm.find('[data-dismiss="modal"]');
            closeButton.on('click', function() {
                employeeForm.validate().resetForm(); // Reset the validation messages
                employeeForm.find('.form-group').removeClass('has-error'); // Remove the error styling
            });
        });


        $(function() {

            // Get the CSRF token value
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var table = $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employees.index') }}",
                columns: [{
                        title: 'Id',
                        data: 'id',
                        name: 'id'
                    },
                    {
                        title: 'First Name',
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        title: 'Last Name',
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        title: 'Email',
                        data: 'email',
                        name: 'email'
                    },
                    {
                        title: 'Created At',
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        title: 'Updated At',
                        data: 'updated_at',
                        name: 'updated_at'
                    },

                    @role('admin')
                        {
                            title: 'Action',
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    @endrole

                ]
            });


            $(document).ready(function() {
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Handle form submission for adding/editing an employee
    $(document).on('click', '.edit-btn', function() {
        var employeeId = $(this).data('id');

        // Set the employee ID in the form
        $('#employeeId').val(employeeId);

        // Update the form labels and button text
        $('#employeeModalLabel').text('Edit Employee');
        $('#saveBtn').text('Update');

        // Perform an AJAX request to fetch the employee data
        $.ajax({
            url: "{{ route('employees.show', ':id') }}".replace(':id', employeeId),
            type: 'GET',
            success: function(response) {
                var employee = response.employee;
                $('#first_name').val(employee.first_name);
                $('#last_name').val(employee.last_name);
                $('#email').val(employee.email);
                // Clear the password field for security reasons
                $('#password').val('');
            }
        });

        // Show the modal
        $('#employeeModal').modal('show');
    });

    // Handle form submission
    $('#employeeForm').submit(function(e) {
        e.preventDefault();

        var employeeId = $('#employeeId').val();
        var url = '';
        var method = '';

        if (employeeId) {
            // Editing an existing employee
            url = "{{ route('employees.update', ':id') }}".replace(':id', employeeId);
            method = 'PUT';
            var successMessage = 'Employee Updated';
        } else {
            // Adding a new employee
            url = "{{ route('employees.store') }}";
            method = 'POST';
            var successMessage = 'Employee Added';
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
                    alert(successMessage);
                }
                resetForm();
            }
        });
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
});





            // Delete employee

            $('#employees-table').on('click', '.delete-btn', function() {
                var employeeId = $(this).data('id');
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Are you sure you want to delete this employee?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('employees.destroy', ':id') }}".replace(':id',
                                employeeId),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }, // Pass the CSRF token in the request header
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Employee Deleted',
                                    text: 'Employee deleted successfully!',
                                });
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });

        });
    </script>