@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Units of Measurement', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Unit of Measurement Management</h3>
                    <p>Manage units of measurement and add new units.</p>
                </div>
                <button class="btn btn-primary" id="btn-uoms-add"><i class="fas fa-plus me-2"></i>Add UoM</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="uoms_table" class="table table-sm table-striped fs-9 mb-0 custom-table">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Abbreviation
                            </th>
                            <th>
                                Type
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

    <script>
        const urls = {
            uomsApi: "{{ route('app.uom.index') }}",
            uomsEdit: "{{ route('app.uom.edit', ['uom' => 'UOM_ID']) }}",
            uomsCreate: "{{ route('app.uom.create') }}"
        };

        const getStatusBadge = (isActive) => {
            const badgeClass = isActive ? 'success' : 'danger';
            const label = isActive ? 'Active' : 'Inactive';

            return `<div class="text-center"><span class="badge badge-phoenix badge-phoenix-${badgeClass}">${label}</span></div>`;
        };

        $(document).ready(function () {
            new App.TableManager({
                csrfToken: "{{ csrf_token() }}",
                restApi: urls.uomsApi,
                entity: "uoms",
                datatable: {
                    api: urls.uomsApi,
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'abbreviation', name: 'abbreviation' },
                        { data: 'type', name: 'type' },
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
                                                <a class="dropdown-item btn-uoms-edit" href="#" data-id="${row.id}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-uoms-delete" href="#" data-id="${row.id}">
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
                        window.location.href = urls.uomsCreate;
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
