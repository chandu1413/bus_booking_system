@extends('layouts.app')

@section('title', 'Operators')
@section('page-title', 'Operators')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <!-- Header Section -->
                <div class="table-header">
                    <div class="header-title">
                        <h4>Operators List</h4>
                        <p>Manage and monitor system access levels</p>
                    </div>
                    {{-- <a href="{{ route('admin.operators.create') }}" class="btn-primary" style="text-decoration: none;">
                + Add Operator
            </a> --}}
                </div>
            </div>

            <div class="card-body">
                <!-- Table Section -->
                <div class="table-responsive min-h-[300px]">
                    <table id="operators-table" class="display">
                        <thead>
                            <tr>
                                <th class="col-id">#</th>
                                <th class="col-user">User</th>
                                <th class="col-role">Role</th>
                                <th class="col-status">Status</th>
                                <th class="col-login">Last Login</th>
                                <th class="col-actions text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will inject rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery 3.6 -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- DataTables CSS/JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('#operators-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.operators.index') }}",
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search operators...",
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'role',
                        name: 'roles.name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'last_login',
                        name: 'last_login',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    },
                ],
                drawCallback: function() {
                    // This ensures your custom styles apply after table redraws
                    $('.dataTables_paginate > span > a').addClass('btn-page');
                }
            });
        });
    </script>
@endpush
