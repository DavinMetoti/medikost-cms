@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Suppliers Management', 'url' => route('app.suppliers.index')],
        ['title' => 'Register', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Register a New Supplier</h3>
            <p>Fill in the form to register a new building material supplier.</p>
        </div>
        <div class="card-body">
            <form id="supplier-registration-form" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="name">Supplier Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. PT Semen Tiga Roda" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="logo">Logo</label>
                    <input class="form-control" type="file" id="logo" name="logo" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the supplier" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. (021) 1234567">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="e.g. supplier@email.com">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="contact_person">Contact Person</label>
                    <input class="form-control" type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="e.g. Budi Santoso">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="website">Website</label>
                    <input class="form-control" type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="npwp">NPWP</label>
                    <input class="form-control" type="text" id="npwp" name="npwp" value="{{ old('npwp') }}" placeholder="e.g. 01.234.567.8-999.000">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="is_active">Is Active?</label>
                    <select class="form-select" id="is_active" name="is_active" required>
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Register Supplier</button>
                </div>

                <div id="responseMessage"></div>
            </form>
        </div>
    </div>

    <script>
        let urls = {
            suppliers: "{{ route('app.suppliers.store') }}",
            index: "{{ route('app.suppliers.index') }}",
        };

        $(document).ready(function () {
            $('#supplier-registration-form').on('submit', function (e) {
                e.preventDefault();

                let logoFile = $('#logo')[0].files[0];

                let formData = new FormData();

                formData.append('name', $('#name').val());
                formData.append('description', $('#description').val());
                formData.append('address', $('#address').val());
                formData.append('phone', $('#phone').val());
                formData.append('email', $('#email').val());
                formData.append('contact_person', $('#contact_person').val());
                formData.append('website', $('#website').val());
                formData.append('npwp', $('#npwp').val());
                formData.append('is_active', $('#is_active').val());
                formData.append('_token', "{{ csrf_token() }}");

                if (logoFile) {
                    const reader = new FileReader();
                    let logoExtension = logoFile.name.split('.').pop();

                    reader.onloadend = function () {
                        let base64Logo = reader.result.split(',')[1];
                        formData.append('logo', base64Logo);
                        formData.append('logo_extentions', logoExtension);

                        sendFormData(formData);
                    };

                    reader.readAsDataURL(logoFile);
                } else {
                    formData.append('logo_extentions', null);
                    sendFormData(formData);
                }

                function sendFormData(formData) {
                    let formHandler = new App.Form(urls.suppliers, formData, this, e, { redirectUrl: urls.index });

                    formHandler.sendRequest()
                        .then(response => {
                            console.log("Success:", response);
                        })
                        .catch(error => {
                            console.log("Error:", error);
                        });
                }
            });
        });
    </script>
@endsection
