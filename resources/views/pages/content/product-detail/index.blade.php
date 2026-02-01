@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Product Detail', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Product Detail Management</h3>
                    <p>Manage your product detail listings efficiently.</p>
                </div>
                <button class="btn btn-primary" id="btn-product-detail-add"><i class="fas fa-plus me-2"></i>Add New Product Detail</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="product-detail_table" class="table table-sm table-striped fs-9 mb-0 custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Kost</th>
                            <th>Room Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Jumlah Kamar Tersedia</th>
                            <th></th>
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
            productDetailsApi: "{{ route('app.product-details.index') }}",
            productDetailsEdit: "{{ route('app.product-details.edit', ['product_detail' => 'PRODUCT_DETAIL_ID']) }}",
            productDetailsCreate: "{{ route('app.product-details.create') }}",
            productDetailsCount: "{{ route('app.product-details.counts') }}",
            productsShow: "{{ route('app.products.show', ['product' => 'PRODUCT_ID']) }}"
        };

        $(document).ready(function () {
            // Fetch product detail counts
            $.ajax({
                url: urls.productDetailsCount,
                success: function (data) {
                    $('#count-all').text(data.all);
                    $('#count-publish').text(data.published);
                    $('#count-draft').text(data.draft);
                },
                error: function () {
                    console.error('Failed to fetch product detail counts.');
                }
            });

            // Initialize DataTable
            const tableManager = new App.TableManager({
                csrfToken: "{{ csrf_token() }}",
                restApi: urls.productDetailsApi,
                entity: "product-detail",
                filter: "all",
                datatable: {
                    api: urls.productDetailsApi,
                    columns: [
                        {
                            data: null,
                            name: 'no',
                            orderable: false,
                            searchable: false,
                            width: '5%',
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'images',
                            name: 'images',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                if (!data) return '<div class="text-center">-</div>';
                                try {
                                    const images = JSON.parse(data.replace(/&quot;/g, '"'));
                                    const firstImage = images.length > 0 ? images[0] : null;
                                    if (firstImage) {
                                        return `<div class="text-center"><img src="{{ asset('storage/product-details') }}/${firstImage}" alt="Product Detail Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></div>`;
                                    }
                                    return '<div class="text-center">-</div>';
                                } catch (e) {
                                    console.error('Invalid JSON for images:', data);
                                    return '<div class="text-center text-danger">Invalid Images</div>';
                                }
                            }
                        },
                        {
                            data: 'product.name',
                            name: 'product_name',
                            searchable: true,
                            render: function (data, type, row) {
                                return `<a href="${urls.productsShow.replace('PRODUCT_ID', row.product.id)}">${data}</a>`;
                            }
                        },
                        {
                            data: 'room_name',
                            name: 'room_name',
                            searchable: true,
                            render: function (data, type, row) {
                                return `<a href="${urls.productDetailsApi}/${row.id}">${data}</a>`;
                            }
                        },
                        {
                            data: 'price',
                            name: 'price',
                            searchable: true,
                            render: function (data) {
                                return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            searchable: true,
                            render: function (data) {
                                const statusLabel = data === 'kosong' ? 'Kosong' : 'Isi';
                                const badgeClass = data === 'kosong' ? 'success' : 'warning';
                                return `<div class="text-center"><span class="badge bg-${badgeClass}">${statusLabel}</span></div>`;
                            }
                        },
                        {
                            data: 'available_rooms',
                            name: 'available_rooms',
                            searchable: true,
                            render: function (data) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            width: '5%',
                            render: function (data, type, row) {
                                return `
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item btn-product-detail-edit" href="#" data-id="${row.id}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-product-detail-delete" href="#" data-id="${row.id}">
                                                    <i class="fas fa-trash-alt me-2 text-danger"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `;
                            }
                        }
                    ]
                },
                on: {
                    edit: function () {},
                    add: function () {
                        window.location.href = urls.productDetailsCreate;
                    },
                    delete: function ({ id }) {
                        console.log("Custom delete for id:", id);
                    },
                    'edit_form.before_shown': function (data) {
                        console.log("Edit form data:", data);
                    }
                }
            });

            // Handle nav-tab click for filtering
            $('#stockDetailsTab a.nav-link').on('click', function (e) {
                e.preventDefault();
                const filter = $(this).attr('id').replace('tab-', '');
                let filterValue = null;
                if (filter === 'publish') {
                    filterValue = 'publish';
                } else if (filter === 'draft') {
                    filterValue = 'draft';
                } else {
                    filterValue = 'all';
                }
                tableManager.reload({
                    filter: filterValue
                });
            });
        });
    </script>

@endsection