@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Units of Measurement', 'url' => route('app.uom.index')],
        ['title' => 'Edit UoM', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Edit Unit of Measurement</h3>
            <p>Update the form to edit the Unit of Measurement.</p>
        </div>
        <div class="card-body">
            <form id="uom-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ $uom->name }}" placeholder="Type unit name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="abbreviation">Abbreviation</label>
                    <input class="form-control" type="text" id="abbreviation" name="abbreviation" value="{{ $uom->abbreviation }}" placeholder="e.g., kg, m, L" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="type">Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="weight" {{ $uom->type == 'weight' ? 'selected' : '' }}>Weight</option>
                        <option value="length" {{ $uom->type == 'length' ? 'selected' : '' }}>Length</option>
                        <option value="volume" {{ $uom->type == 'volume' ? 'selected' : '' }}>Volume</option>
                        <option value="area" {{ $uom->type == 'area' ? 'selected' : '' }}>Area</option>
                        <option value="count" {{ $uom->type == 'count' ? 'selected' : '' }}>Count</option>
                        <option value="time" {{ $uom->type == 'time' ? 'selected' : '' }}>Time</option>
                        <option value="temperature" {{ $uom->type == 'temperature' ? 'selected' : '' }}>Temperature</option>
                        <option value="electrical" {{ $uom->type == 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="percentage" {{ $uom->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="general" {{ $uom->type == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $uom->is_active ? 'checked' : '' }}>
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
            // Initialize Select2 for type
            new App.Select2Wrapper('#type', {
                placeholder: 'Silakan pilih...',
            });

            const uomId = @json($uom->id);
            let urls = {
                uoms: "{{ route('app.uom.update', ['uom' => '__UOM_ID__']) }}".replace('__UOM_ID__', uomId),
                index: "{{ route('app.uom.index') }}",
            };
            $('#uom-edit-form').on('submit', function (e) {
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
