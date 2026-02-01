@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Booking Management', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Booking Management</h3>
                    <p>Manage customer bookings for kost rooms.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="booking_table" class="table table-sm table-striped fs-9 mb-0 custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Name</th>
                            <th>Kost Name</th>
                            <th>Room Name</th>
                            <th>Check-in Date</th>
                            <th>Check-out Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <!-- Rows will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const urls = {
            bookingsApi: "{{ route('app.bookings.index') }}",
            bookingsDatatable: "{{ route('app.bookings.datatable') }}"
        };

        $(document).ready(function () {
            // Initialize DataTable
            $('#booking_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: urls.bookingsDatatable,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        width: '5%',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'user_name', name: 'user.name' },
                    { data: 'kost_name', name: 'productDetail.product.name' },
                    { data: 'room_name', name: 'productDetail.room_name' },
                    { data: 'check_in_date', name: 'check_in_date' },
                    { data: 'check_out_date', name: 'check_out_date' },
                    { data: 'total_price', name: 'total_price', orderable: false },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data) {
                            const badgeClass = data === 'confirmed' ? 'success' : (data === 'pending' ? 'warning' : 'danger');
                            return `<span class="badge bg-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    }
                ],
                pageLength: 25,
                responsive: true,
                order: [[0, 'desc']]
            });
        });
    </script>

@endsection