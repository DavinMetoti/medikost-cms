@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Locations', 'url' => route('app.locations.index')],
        ['title' => 'Edit Location', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Edit Location</h3>
            <p>Update the form to edit the Location.</p>
        </div>
        <div class="card-body">
            <form id="location-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                    <input class="form-control" type="text" id="code" name="code" value="{{ $location->code }}" placeholder="Type location code" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ $location->name }}" placeholder="Type location name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Type description">{{ $location->description }}</textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $location->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

                <div id="responseMessage"></div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const locationId = @json($location->id);
            let urls = {
                locations: "{{ route('app.locations.update', ['location' => '__LOCATION_ID__']) }}".replace('__LOCATION_ID__', locationId),
                index: "{{ route('app.locations.index') }}",
            };

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

            // Set initial values
            @if($location->warehouse)
                $('#warehouse_id').append(new Option('{{ $location->warehouse->code }} - {{ $location->warehouse->name }}', '{{ $location->warehouse->id }}', true, true)).trigger('change');
            @endif

            @if($location->locationType)
                $('#location_type_id').append(new Option('{{ $location->locationType->code }} - {{ $location->locationType->name }}', '{{ $location->locationType->id }}', true, true)).trigger('change');
            @endif

            @if($location->parent)
                $('#parent_id').append(new Option('{{ $location->parent->code }} - {{ $location->parent->name }}', '{{ $location->parent->id }}', true, true)).trigger('change');
            @endif

            $('#warehouse_id').on('change', function () {
                $('#parent_id').val(null).trigger('change');
            });

            $('#location-edit-form').on('submit', function (e) {
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