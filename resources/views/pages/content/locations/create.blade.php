@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Locations', 'url' => route('app.locations.index')],
        ['title' => 'Add Location', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Create New Location</h3>
            <p>Fill in the form to create a new location.</p>
        </div>
        <div class="card-body">
            <form id="location-create-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="warehouse_id">Warehouse</label>
                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                        <option value="">Select Warehouse</option>
                        <!-- Options will be populated -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location_type_id">Location Type</label>
                    <select class="form-control" id="location_type_id" name="location_type_id" required>
                        <option value="">Select Location Type</option>
                        <!-- Options will be populated -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="parent_id">Parent Location</label>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="">Select Parent Location (Optional)</option>
                        <!-- Options will be populated based on warehouse -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="code">Code</label>
                    <input class="form-control" type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Type location code" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Type location name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Type description">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
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
            locations: "{{ route('app.locations.store') }}",
            index: "{{ route('app.locations.index') }}",
        }

        $(document).ready(function () {
            // Initialize Select2 for selects
            new App.Select2Wrapper('#warehouse_id', {
                placeholder: 'Select Warehouse',
                ajax: "{{ route('app.warehouses.select') }}",
            });

            new App.Select2Wrapper('#location_type_id', {
                placeholder: 'Select Location Type',
                ajax: "{{ route('app.location-types.select') }}",
            });

            new App.Select2Wrapper('#parent_id', {
                placeholder: 'Select Parent Location',
                ajax: {
                    url: "{{ route('app.locations.select') }}",
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            search: params.term,
                            warehouse_id: $('#warehouse_id').val()
                        };
                    },
                    processResults: function (data) {
                        if (data.results && Array.isArray(data.results)) {
                            return {
                                results: data.results.map(item => ({ id: item.id, text: item.location_display }))
                            };
                        } else {
                            console.error("Invalid response format:", data);
                            return { results: [] };
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText);
                        return { results: [] };
                    }
                }
            });

            $('#warehouse_id').on('change', function () {
                $('#parent_id').val(null).trigger('change');
            });

            $('#location-create-form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.locations, formData, this, e, { redirectUrl: urls.index });

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