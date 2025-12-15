@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Warehouses', 'url' => route('app.warehouses.index')],
        ['title' => 'Add Warehouse', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Create New Warehouse</h3>
            <p>Fill in the form to create a new warehouse location.</p>
        </div>
        <div class="card-body">
            <form id="warehouse-create-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="code">Code</label>
                    <input class="form-control" type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Type warehouse code" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Type warehouse name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Type warehouse address">{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Type warehouse description">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="parent_id">Parent Warehouse</label>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="">Select Parent Warehouse (Optional)</option>
                        <!-- Parent warehouses will be loaded via AJAX -->
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>

                <div id="responseMessage"></div>
            </form>
        </div>
    </div>

    <script>
        let urls = {
            warehouses: "{{ route('app.warehouses.store') }}",
            index: "{{ route('app.warehouses.index') }}",
            parentSelect: "{{ route('app.warehouses.select') }}",
        }

        $(document).ready(function () {
            // Initialize Select2 for parent warehouse
            new App.Select2Wrapper('#parent_id', {
                ajax: urls.parentSelect,
                placeholder: 'Silakan pilih...',
            });

            $('#warehouse-create-form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.warehouses, formData, this, e, { redirectUrl: urls.index });

                formHandler.sendRequest()
                    .then(response => {
                        console.log("Success:", response);
                    })
                    .catch(error => {
                        console.log("Error:", error);
                    });
            });
        });
    </script>
@endsection
