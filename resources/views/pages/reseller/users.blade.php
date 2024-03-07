@extends('layouts.admin')

@section('title', 'All Users')

@push('after-style')
    <style>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }

        .dtfc-fixed-right {
            background-color: rgba(255, 255, 255, 0.768);
        }
    </style>
@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-white py-4 mb-4">
                            <div class="row col-12">
                                <div class="col-md-6 text-left col-12 align-self-center">
                                    <h4>All Users</h4>
                                </div>
                                <div class="col-md-6 text-right col-12 align-self-center">
                                    <a href="{{ route('logoutAllUsers') }}" class="btn btn-main">Logout All User</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pb-5 pt-2 w-100">
                            <div class="table-responsive table-invoice overflow-hidden">
                                <table id="tableUsers" class="table w-100">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Role</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th>Born</th> <!-- New heading -->
                                            <th>Gender</th>  <!-- New heading -->
                                            <th>Country</th> <!-- New heading -->
                                            <th>Invitation Code</th> <!-- New heading -->
                                            <th>Password</th>
                                            <th>Address</th>
                                            <th>Join Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- data table ajax --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="modalUsers" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title d-inline">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-input">
                        @csrf
                        <input type="hidden" class="form-control form-control-sm inputtext" id="id" name="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control form-control-sm inputtext" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control form-control-sm inputtext" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control form-control-sm inputtext" id="phone" name="phone" required>
                        </div>
                        <label for="balance" class="form-control-label">Balance</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" id="balance" name="balance" placeholder="0.00" oninput="handleInput(this)"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="born">Born</label>
                            <input type="date" class="form-control form-control-sm inputtext" id="born" name="born" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control form-control-sm inputtext" id="gender" name="gender" required>
                                <option value="Man">Man</option>
                                <option value="Woman">Woman</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control form-control-sm inputtext" id="country" name="country" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control form-control-sm inputtext" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control form-control-sm inputtext" id="address" name="address" required>
                        </div>
                        <button class="btn btn-success" id="simpan" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        jQuery(document).ready(function ($) {
            var table = $('#tableUsers').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                order: [[0, "asc"]],
                ajax: {
                    url: '{{ route("getUsers") }}',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'role', name: 'role',
                        render: function(data) {
                            if (data == 2) {
                                return 'Seller';
                            } else if (data == 3) {
                                return 'Customer';
                            } else {
                                return 'Unknown';
                            }
                        },
                    },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'balance', name: 'balance',
                        render: function(data) {
                            return '$'+data;
                        },
                    },
                    { data: 'born', name: 'born' },
                    { data: 'gender', name: 'gender' },
                    { data: 'country', name: 'country' },
                    { data: 'invitation_code', name: 'invitation_code',
                        render: function(data) {
                            return data ? data : '-';
                            },
                    },
                    { data: 'password', name: 'password'},
                    { data: 'address', name: 'address' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                ],
            });

            // ... (kode JavaScript tetap sama) ...

            $('body').on('click', '.updateItems', function () {
                var id = $(this).data('id');
                $.get("{{ route('users.index') }}"+'/'+id, function (data) {
                    $('#modalUsers').modal('show');
                    $('#id').val(id);
                    $('#role').val(data.data.role);
                    $('#name').val(data.data.name);
                    $('#email').val(data.data.email);
                    $('#phone').val(data.data.phone);
                    $('#balance').val(data.data.balance);
                    $('#born').val(data.data.born);
                    $('#gender').val(data.data.gender);
                    $('#country').val(data.data.country);
                    $('#password').val(data.data.password);
                    $('#address').val(data.data.address);
                });
            });

            $('body').on('click', '#simpan', function (e) {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    var data = $('#form-input');
                    var formData = data.serialize();

                    $.ajax({
                        type: "POST",
                        url: "{{ route('users.store') }}",
                        data: formData,

                        success: function (data) {
                            if (data.errors) {
                                console.log('error');
                            }
                            if (data.success) {
                                $('#modalUsers').modal('hide');
                                table.ajax.reload();

                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: data.success 
                                });

                            }
                        }
                    });

                    });

            $('body').on('click', '.deleteItems', function (e) {
            e.preventDefault();

            Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'If you delete a user, all transactions or data related to the user will be lost!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).data('id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        url: "{{ route('users.index' ) }}" + '/' + id,
                        method: 'DELETE',

                        success: function (data) {
                                table.ajax.reload();
                            const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: data.success 
                                });
                        } 
                    });
            }
        });
       
                
                });

            // ... (kode JavaScript tetap sama) ...
        });
    </script>
@endpush
