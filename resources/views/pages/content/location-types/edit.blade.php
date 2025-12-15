@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Location Types', 'url' => route('app.location-types.index')],
        ['title' => 'Edit Location Type', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Edit Location Type</h3>
            <p>Update the form to edit the Location Type.</p>
        </div>
        <div class="card-body">
            <form id="location-type-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="code">Code</label>
                    <input class="form-control" type="text" id="code" name="code" value="{{ $locationType->code }}" placeholder="Type location type code" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ $locationType->name }}" placeholder="Type location type name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="level_order">Level Order</label>
                    <input class="form-control" type="number" id="level_order" name="level_order" value="{{ $locationType->level_order }}" placeholder="e.g., 1, 2, 3" required>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $locationType->is_active ? 'checked' : '' }}>
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
            const locationTypeId = @json($locationType->id);
            let urls = {
                locationTypes: "{{ route('app.location-types.update', ['location_type' => '__LOCATION_TYPE_ID__']) }}".replace('__LOCATION_TYPE_ID__', locationTypeId),
                index: "{{ route('app.location-types.index') }}",
            };
            $('#location-type-edit-form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.locationTypes, formData, this, e, { redirectUrl: urls.index });

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