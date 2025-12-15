@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Locations', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Location Management</h3>
                    <p>Manage locations and add new locations.</p>
                </div>
                <button class="btn btn-primary" id="btn-locations-add"><i class="fas fa-plus me-2"></i>Add Location</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="locations_table" class="table table-sm table-striped fs-9 mb-0 custom-table locations-tree">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Warehouse
                            </th>
                            <th class="text-center">
                                Active
                            </th>
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
        .locations-tree .tree-connector {
            color: #6c757d;
            font-weight: bold;
        }

        .locations-tree .tree-level-1 {
            position: relative;
            padding-left: 20px;
        }

        .locations-tree .tree-level-1::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: gray;
        }

        .locations-tree .tree-level-1::after {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            width: 10px;
            height: 1px;
            background: gray;
        }

        .locations-tree .tree-level-2 {
            position: relative;
            padding-left: 40px;
        }

        .locations-tree .tree-level-2::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: gray;
        }

        .locations-tree .tree-level-2::after {
            content: '';
            position: absolute;
            left: 30px;
            top: 50%;
            width: 10px;
            height: 1px;
            background: gray;
        }

        .locations-tree .tree-level-3 {
            position: relative;
            padding-left: 60px;
        }

        .locations-tree .tree-level-3::before {
            content: '';
            position: absolute;
            left: 70px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: gray;
        }

        .locations-tree .tree-level-3::after {
            content: '';
            position: absolute;
            left: 70px;
            top: 50%;
            width: 10px;
            height: 1px;
            background: gray;
        }

        .locations-tree .tree-level-4 {
            position: relative;
            padding-left: 80px;
        }

        .locations-tree .tree-level-4::before {
            content: '';
            position: absolute;
            left: 70px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: gray;
        }

        .locations-tree .tree-level-4::after {
            content: '';
            position: absolute;
            left: 70px;
            top: 50%;
            width: 10px;
            height: 1px;
            background: gray;
        }

        .locations-tree .tree-level-5 {
            position: relative;
            padding-left: 100px;
        }

        .locations-tree .tree-level-5::before {
            content: '';
            position: absolute;
            left: 90px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: gray;
        }

        .locations-tree .tree-level-5::after {
            content: '';
            position: absolute;
            left: 90px;
            top: 50%;
            width: 10px;
            height: 1px;
            background: gray;
        }
    </style>

    <script>
        const urls = {
            locationsApi: "{{ route('app.locations.index') }}",
            locationsEdit: "{{ route('app.locations.edit', ['location' => 'LOCATION_ID']) }}",
            locationsCreate: "{{ route('app.locations.create') }}",
            locationsShow: "{{ route('app.locations.show', ['location' => 'LOCATION_ID']) }}"
        };

        const getStatusBadge = (isActive) => {
            const badgeClass = isActive ? 'success' : 'danger';
            const label = isActive ? 'Active' : 'Inactive';

            return `<div class="text-center"><span class="badge badge-phoenix badge-phoenix-${badgeClass}">${label}</span></div>`;
        };

        $(document).ready(function () {
            new App.TableManager({
                csrfToken: "{{ csrf_token() }}",
                restApi: urls.locationsApi,
                entity: "locations",
                datatable: {
                    api: urls.locationsApi,
                    serverSide: false,
                    columns: [
                        { data: 'location_display', name: 'location_display', orderable: false, render: function (data, type, row) {
                            const showUrl = urls.locationsShow.replace('LOCATION_ID', row.id);
                            const levelClass = row.level ? `tree-level-${row.level}` : '';
                            return `<a href="${showUrl}" class="text-decoration-none ${levelClass}">${data}</a>`;
                        } },
                        { data: 'warehouse', name: 'warehouse' },
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
                                                <a class="dropdown-item btn-locations-edit" href="#" data-id="${row.id}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-locations-delete" href="#" data-id="${row.id}">
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
                        window.location.href = urls.locationsCreate;
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