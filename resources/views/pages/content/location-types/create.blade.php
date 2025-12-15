@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Location Types', 'url' => route('app.location-types.index')],
        ['title' => 'Add Location Type', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Create New Location Type</h3>
            <p>Fill in the form to create a new location type.</p>
        </div>
        <div class="card-body">
            <form id="location-type-create-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="code">Code</label>
                    <input class="form-control" type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Type location type code" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Type location type name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="level_order">Level Order</label>
                    <input class="form-control" type="number" id="level_order" name="level_order" value="{{ old('level_order') }}" placeholder="e.g., 1, 2, 3" required>
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
            locationTypes: "{{ route('app.location-types.store') }}",
            index: "{{ route('app.location-types.index') }}",
        }

        $(document).ready(function () {
            $('#location-type-create-form').on('submit', function (e) {
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