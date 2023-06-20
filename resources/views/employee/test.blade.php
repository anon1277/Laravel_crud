<!-- index.blade.php -->

@extends('layouts.layout')


@section('content')
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="card">
            <div class="card-header">
                Employee List
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="employeeForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="employeeId">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- Include Bootstrap library -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Include SweetAlert 2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">

    <!-- Include jQuery library -->

    <!-- Include SweetAlert 2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            // Get the CSRF token value
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var table = $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employees.index') }}",
                columns: [{
                    title: 'Id' ,
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
                    @role('admin') {
                                title: 'Action'
                                , data: 'action'
                                , name: 'action'
                                , orderable: false
                                , searchable: false
                            } , @endrole



                ]
            });

            // Open the modal for adding/editing an employee
            $('#employees-table').on('click', '.edit-btn', function() {
                var employeeId = $(this).data('id');
                $('#employeeId').val(employeeId);

                $.ajax({
                    url: "{{ route('employees.show', ':id') }}".replace(':id', employeeId),
                    type: 'GET',
                    success: function(response) {
                        var employee = response.employee;
                        // alert(employee.first_name);
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
                var url = (employeeId) ? "{{ route('employees.update', ':id') }}".replace(':id',
                    employeeId) : "{{ route('employees.store') }}";
                var method = (employeeId) ? 'PUT' : 'POST';

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
                            $('#employeeForm')[0].reset(); // Reset the form

                        }
                    }
                });

                Swal.fire({
                                icon: 'success',
                                title: 'Employee Updated',
                                text: 'Data updated successfully!',

                            });
                // Close the form
                $('#employeeModal').modal('hide');
                table.ajax.reload();
            });


            // Delete employee
            $('#employees-table').on('click', '.delete-btn', function() {
                if (confirm('Are you sure you want to delete this employee?')) {
                    var employeeId = $(this).data('id');
                    $.ajax({
                        url: "{{ route('employees.destroy', ':id') }}".replace(':id', employeeId),
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }, // Pass the CSRF token in the request header
                        success: function(response) {
                            table.ajax.reload();
                        }
                    });
                }
            });
        });
    </script>
