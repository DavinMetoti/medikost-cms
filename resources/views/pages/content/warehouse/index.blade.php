@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Warehouses', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Warehouse Management</h3>
                    <p>Manage warehouses and add new warehouse locations.</p>
                </div>
                <button class="btn btn-primary" id="btn-warehouse-add"><i class="fas fa-plus me-2"></i>Add Warehouse</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="warehouse_table" class="table table-sm table-striped fs-9 mb-0 custom-table warehouse-tree">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center">Active</th>
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

    <style>
        .warehouse-tree .tree-connector {
            color: #6c757d;
            font-weight: bold;
        }

        .warehouse-tree .tree-level-1 {
            padding-left: 20px;
        }

        .warehouse-tree .tree-level-2 {
            padding-left: 40px;
        }

        .warehouse-tree .tree-level-3 {
            padding-left: 60px;
        }

        .warehouse-tree .tree-level-4 {
            padding-left: 80px;
        }

        .warehouse-tree .tree-level-5 {
            padding-left: 100px;
        }
    </style>

    <script>
        const urls = {
            warehousesApi: "{{ route('app.warehouses.index') }}",
            warehousesEdit: "{{ route('app.warehouses.edit', ['warehouse' => 'WAREHOUSE_ID']) }}",
            warehousesCreate: "{{ route('app.warehouses.create') }}",
            warehousesShow: "{{ route('app.warehouses.show', ['warehouse' => 'WAREHOUSE_ID']) }}"
        };

        const getStatusBadge = (isActive) => {
            const badgeClass = isActive ? 'success' : 'danger';
            const label = isActive ? 'Active' : 'Inactive';

            return `<div class="text-center"><span class="badge badge-phoenix badge-phoenix-${badgeClass}">${label}</span></div>`;
        };

        $(document).ready(function () {

            new App.TableManager({
                csrfToken: "{{ csrf_token() }}",
                restApi: urls.warehousesApi,
                entity: "warehouse",
                datatable: {
                    api: urls.warehousesApi,
                    serverSide: false,
                    columns: [
                        { 
                            data: 'code', 
                            name: 'code',
                            width: '10%',
                            orderable: false
                        },
                        {
                            data: 'name_display',
                            name: 'name_display',
                            orderable: false,
                            render: function (data, type, row) {
                                const showUrl = urls.warehousesShow.replace('WAREHOUSE_ID', row.id);
                                return `<a href="${showUrl}" class="text-decoration-none">${data}</a>`;
                            }
                        },
                        { 
                            data: 'description', 
                            name: 'description',
                            orderable: false
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            orderable: false,
                            width: '10%',
                            render: getStatusBadge
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
                                                <a class="dropdown-item btn-warehouse-edit" href="#" data-id="${row.id}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-warehouse-delete" href="#" data-id="${row.id}">
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
                        window.location.href = urls.warehousesCreate;
                    },
                    delete: function ({ id }) {
                        console.log("Custom delete for id:", id);
                    },
                    'edit_form.before_shown': function (data) {
                        console.log("Edit form data:", data);
                    }
                }
            });
        });
    </script>

@endsection
