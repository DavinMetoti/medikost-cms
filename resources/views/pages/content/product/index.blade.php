@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Product', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Product Management</h3>
                    <p>Manage your kost listings efficiently.</p>
                </div>
                <button class="btn btn-primary" id="btn-product-add"><i class="fas fa-plus me-2"></i>Add New Product</button>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-underline optionChainTableHeader gap-0 flex-nowrap scrollbar mb-4 px-3" id="stockDetailsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 text-nowrap ps-0 pe-3 active" id="tab-all" href="#all-tab" data-bs-toggle="tab" role="tab" aria-controls="all-tab" aria-selected="true">
                        All Products <span class="badge bg-secondary" id="count-all">0</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 text-nowrap px-3" id="tab-publish" href="#publish-tab" data-bs-toggle="tab" role="tab" aria-controls="publish-tab" aria-selected="false" tabindex="-1">
                        Published <span class="badge bg-secondary" id="count-publish">0</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 text-nowrap px-3" id="tab-draft" href="#draft-tab" data-bs-toggle="tab" role="tab" aria-controls="draft-tab" aria-selected="false" tabindex="-1">
                        Drafts <span class="badge bg-secondary" id="count-draft">0</span>
                    </a>
                </li>
                <li class="nav-item flex-1 d-none d-md-inline d-xl-none d-xxl-inline" role="presentation">
                    <a class="nav-link pt-0 text-nowrap px-3 disabled h-100" id="tab-empty1" href="stock-details.html#empty1-tab" data-bs-toggle="tab" role="tab" aria-selected="false" tabindex="-1"></a>
                </li>
            </ul>
            <div class="table-responsive">
                <table id="product_table" class="table table-sm table-striped fs-9 mb-0 custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Address</th>
                            <th>Distance to Kariadi</th>
                            <th>Whatsapp</th>
                            <th>Jumlah Kamar</th>
                            <th>Status</th>
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
            productsApi: "{{ route('app.products.index') }}",
            productsEdit: "{{ route('app.products.edit', ['product' => 'PRODUCT_ID']) }}",
            productsCreate: "{{ route('app.products.create') }}",
            productsCount: "{{ route('app.products.counts') }}"
        };

        const getRoleBadge = (role) => {
            const badgeMap = {
                admin: 'primary',
                cashier: 'secondary',
                warehouse: 'success',
                customer: 'info',
                guest: 'warning'
            };

            const badgeClass = badgeMap[role] || 'warning';
            const label = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Guest';

            return `<div class="text-center"><span class="badge badge-phoenix badge-phoenix-${badgeClass}">${label}</span></div>`;
        };

        $(document).ready(function () {
            // Fetch product counts
            $.ajax({
                url: urls.productsCount,
                success: function (data) {
                    $('#count-all').text(data.all);
                    $('#count-publish').text(data.published);
                    $('#count-draft').text(data.draft);
                },
                error: function () {
                    console.error('Failed to fetch product counts.');
                }
            });

            // Initialize DataTable
            const tableManager = new App.TableManager({
                csrfToken: "{{ csrf_token() }}",
                restApi: urls.productsApi,
                entity: "product",
                filter: "all",
                datatable: {
                    api: urls.productsApi,
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
                                    const images = JSON.parse(data.replace(/&quot;/g, '"')); // Parse JSON safely
                                    const firstImage = images.length > 0 ? images[0] : null;
                                    if (firstImage) {
                                        return `<div class="text-center"><img src="{{ asset('storage/products') }}/${firstImage}" alt="Product Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></div>`;
                                    }
                                    return '<div class="text-center">-</div>';
                                } catch (e) {
                                    console.error('Invalid JSON for images:', data);
                                    return '<div class="text-center text-danger">Invalid Images</div>';
                                }
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function (data, type, row) {
                                return `<a href="${urls.productsApi}/${row.id}">${data}</a>`;
                            }
                        },
                        { data: 'category', name: 'category' },
                        { data: 'address', name: 'address' },
                        {
                            data: 'distance_to_kariadi',
                            name: 'distance_to_kariadi',
                            render: function (data) {
                                return data ? `${data} km` : '-';
                            }
                        },
                        { data: 'whatsapp', name: 'whatsapp' },
                        {
                            data: 'product_details',
                            name: 'product_details',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return data ? data.length : 0;
                            }
                        },
                        {
                            data: 'is_published',
                            name: 'is_published',
                            orderable: false,
                            width: '10%',
                            render: function (data, type, row) {
                                const published = data ? 'Published' : 'Draft';
                                const badgeClass = data ? 'success' : 'warning';
                                return `<div class="text-center"><span class="badge bg-${badgeClass}">${published}</span></div>`;
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
                                                <a class="dropdown-item btn-product-edit" href="#" data-id="${row.id}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-product-delete" href="#" data-id="${row.id}">
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
                    edit: function () {

                    },
                    add: function () {
                        window.location.href = urls.productsCreate;
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
                const filter = $(this).attr('id').replace('tab-', ''); // Extract filter from tab ID (e.g., 'all', 'publish', 'draft')

                // Determine filter value for is_published
                let filterValue = null;
                if (filter === 'publish') {
                    filterValue = 'publish'; // Published
                } else if (filter === 'draft') {
                    filterValue = 'draft'; // Draft
                } else {
                    filterValue = 'all'; // All
                }

                // Reload table with the selected filter
                tableManager.reload({
                    filter: filterValue // Pass the filter value to the reload method
                });
            });
        });
    </script>

@endsection
