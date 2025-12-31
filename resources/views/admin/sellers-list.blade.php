@extends('layouts.app')

@push('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .pagination svg {
            width: 20px;
            /* Fixes huge arrow issue if Tailwind is leaking in */
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Force search bar to right */
        .dataTables_filter {
            width: 100%;
            display: flex !important;
            justify-content: flex-end !important;
            text-align: right !important;
        }

        .dataTables_filter label {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
        }

        /* Search input styling */
        .dataTables_filter input {
            width: 260px;
            border-radius: 8px;
            padding: 6px 10px;
        }

        /* Fix table headers */
        table.dataTable thead th {
            vertical-align: middle;
            text-align: left;
        }

        /* Remove layout issues caused by SB Admin rows */
        .dataTables_wrapper .row {
            margin: 0 !important;
            width: 100% !important;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="row mt-4 mb-2">
            <div class="col-md-8">
                <h1 class="h3 text-gray-800 mb-0">Seller Management</h1>
            </div>

            <div class="col-md-4 text-end">
                <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Seller
                </a>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('view') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Sellers</li>
        </ol>

        {{-- Card --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registered Sellers List</h6>
            </div>

            <div class="card-body">

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="sellersTable" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Location</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->id }}</td>
                                    <td><strong>{{ $seller->name }}</strong></td>
                                    <td>{{ $seller->email }}</td>
                                    <td>{{ $seller->mobile_no }}</td>
                                    <td>{{ $seller->state }}, {{ $seller->country }}</td>
                                    <td>{{ $seller->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No sellers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Laravel Pagination --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $sellers->firstItem() }} to {{ $sellers->lastItem() }} of {{ $sellers->total() }}
                            entries
                        </div>

                        <div class="pagination-container">
                            {{ $sellers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#sellersTable').DataTable({

                paging: false, // Laravel pagination
                info: false,
                ordering: true,
                searching: true,

                // Only search bar on top
                dom: '<"mb-2"f>t',

                language: {
                    search: "Filter Page:"
                }
            });

        });
    </script>
@endpush
