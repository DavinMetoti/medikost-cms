@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Units of Measurement', 'url' => route('app.uom.index')],
        ['title' => 'Add UoM', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Create New Unit of Measurement</h3>
            <p>Fill in the form to create a new unit of measurement.</p>
        </div>
        <div class="card-body">
            <form id="uom-create-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Type unit name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="abbreviation">Abbreviation</label>
                    <input class="form-control" type="text" id="abbreviation" name="abbreviation" value="{{ old('abbreviation') }}" placeholder="e.g., kg, m, L" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="type">Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="weight" {{ old('type') == 'weight' ? 'selected' : '' }}>Weight</option>
                        <option value="length" {{ old('type') == 'length' ? 'selected' : '' }}>Length</option>
                        <option value="volume" {{ old('type') == 'volume' ? 'selected' : '' }}>Volume</option>
                        <option value="area" {{ old('type') == 'area' ? 'selected' : '' }}>Area</option>
                        <option value="count" {{ old('type') == 'count' ? 'selected' : '' }}>Count</option>
                        <option value="time" {{ old('type') == 'time' ? 'selected' : '' }}>Time</option>
                        <option value="temperature" {{ old('type') == 'temperature' ? 'selected' : '' }}>Temperature</option>
                        <option value="electrical" {{ old('type') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
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
            uoms: "{{ route('app.uom.store') }}",
            index: "{{ route('app.uom.index') }}",
        }

        $(document).ready(function () {
            // Initialize Select2 for type
            new App.Select2Wrapper('#type', {
                placeholder: 'Silakan pilih...',
            });

            $('#uom-create-form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.uoms, formData, this, e, { redirectUrl: urls.index });

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
