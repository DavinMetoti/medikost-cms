@extends('pages.content.index')

@section('main')
<x-breadcrumb :items="[
        ['title' => 'Product Management', 'url' => route('app.products.index')],
        ['title' => 'Create', 'url' => '#']
    ]" />
<div class="row align-items-center justify-content-between g-3">
    <div class="col-auto">
        <h3>Register a New Product</h3>
        <p>Fill in the form to create a new product account.</p>
    </div>
    <div class="col-auto">
        <div class="row g-2 g-sm-3">
            <div class="col-auto"><button class="btn btn-phoenix-danger" id="discard-btn"><span class="fas fa-trash-alt me-2"></span>Discard</button></div>
            <div class="col-auto"><button class="btn btn-secondary" id="draft-btn"><span class="fas fa-folder-open me-2"></span>Save Draf</button></div>
            <div class="col-auto"><button class="btn btn-primary" id="submit-btn"><span class="fas fa-check me-2"></span>Publish Product</button></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Product</h5>
            </div>
            <div class="card-body">
                <div id="responseMessage"></div>

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="Your Product Name" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="Campur">Campur</option>
                        <option value="Putri">Putri</option>
                        <option value="Putra">Putra</option>
                    </select>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Product Address" rows="3"></textarea>
                </div>

                {{-- Distance to Kariadi --}}
                <div class="mb-3">
                    <label for="distance_to_kariadi" class="form-label">Distance to Kariadi (km)</label>
                    <input class="form-control" type="number" step="0.01" id="distance_to_kariadi" name="distance_to_kariadi" placeholder="Distance in km">
                </div>

                {{-- Whatsapp --}}
                <div class="mb-3">
                    <label for="whatsapp" class="form-label">Whatsapp</label>
                    <input class="form-control" type="text" id="whatsapp" name="whatsapp" placeholder="Whatsapp number">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <div id="description" class="form-control" style="min-height: 200px;"></div>
                </div>

                {{-- Google Maps Link --}}
                <div class="mb-3">
                    <label for="google_maps_link" class="form-label">Google Maps Link</label>
                    <input class="form-control" type="url" id="google_maps_link" name="google_maps_link" placeholder="https://maps.google.com/...">
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input class="form-control currency" type="text" id="price" name="price" placeholder="Product Price" aria-label="Amount (to the nearest dollar)">
                        <span class="input-group-text">.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Facilities</h5>
            </div>
            <div class="card-body">
                <div id="facilities-container">
                    <!-- Default headers -->
                    <div class="facility-header mb-3 border p-3 rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Peraturan Kost">
                        </div>
                        <div class="items-container">
                            <!-- Items will be added here -->
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                            <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                        </div>
                    </div>
                    <div class="facility-header mb-3 border p-3 rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Fasilitas umum">
                        </div>
                        <div class="items-container">
                            <!-- Items will be added here -->
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                            <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                        </div>
                    </div>
                    <div class="facility-header mb-3 border p-3 rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Fasilitas parkir">
                        </div>
                        <div class="items-container">
                            <!-- Items will be added here -->
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                            <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                        </div>
                    </div>
                    <div class="facility-header mb-3 border p-3 rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Ketentuan pengajuan sewa">
                        </div>
                        <div class="items-container">
                            <!-- Items will be added here -->
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                            <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                        </div>
                    </div>
                </div>
                <button id="add-header" class="btn btn-primary mt-3">Add Header</button>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Image</h5>
            </div>
            <div class="card-body">
                <form class="dropzone dropzone-multiple p-0" id="dropzone-multiple" data-dropzone="data-dropzone" action="#!">
                    <div class="fallback">
                        <input name="file" type="file" multiple="multiple" />
                    </div>
                    <div class="dz-message my-0" data-dz-message="data-dz-message">
                        <img class="me-2" src="../../../assets/img/icons/cloud-upload.svg" width="25" alt="" />
                        Drop your files here
                    </div>
                    <div class="dz-preview dz-preview-multiple m-0 d-flex flex-column">
                        <div class="d-flex mb-3 pb-3 border-bottom border-translucent media">
                            <div class="border p-2 rounded-2 me-2">
                                <img class="rounded-2 dz-image" src="../../../assets/img/icons/file.png" alt="..." data-dz-thumbnail="data-dz-thumbnail" />
                            </div>
                            <div class="flex-1 d-flex flex-between-center">
                                <div>
                                    <h6 data-dz-name="data-dz-name"></h6>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 fs-9 text-body-quaternary lh-1" data-dz-size="data-dz-size"></p>
                                    </div>
                                    <span class="fs-10 text-danger" data-dz-errormessage="data-dz-errormessage"></span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-body-tertiary btn-sm dropdown-toggle btn-reveal dropdown-caret-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fas fa-ellipsis-h"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end border border-translucent py-2">
                                        <a class="dropdown-item" href="#!" data-dz-remove="data-dz-remove">Remove File</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- JS Section --}}
<script>
    let urls = {
        products: "{{ route('app.products.store') }}",
        categorySelect: "{{ route('app.categories.select') }}",
        supplierSelect: "{{ route('app.suppliers.select') }}",
        uomSelect: "{{ route('app.uom.select') }}",
        index: "{{ route('app.products.index') }}",
    };

    let editor = null;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Initialize Summernote Editor
        if (editor) {
            editor.destroy();
        }
        editor = new App.SummernoteEditor('#description');

        // Facilities functionality
        $('#add-header').on('click', function() {
            const headerHtml = `
                <div class="facility-header mb-3 border p-3 rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header">
                    </div>
                    <div class="items-container">
                        <!-- Items will be added here -->
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                        <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                    </div>
                </div>
            `;
            $('#facilities-container').append(headerHtml);
        });

        $('#facilities-container').on('click', '.add-item', function() {
            const container = $(this).closest('.facility-header').find('.items-container');
            const itemCount = container.find('.item-input').length + 1;
            const itemHtml = `
                <div class="d-flex align-items-center mb-1">
                    <span class="item-number me-2">${itemCount}.</span>
                    <input type="text" class="form-control item-input" placeholder="Item">
                    <button class="btn btn-danger btn-sm remove-item ms-2">Remove</button>
                </div>
            `;
            container.append(itemHtml);
        });

        $('#facilities-container').on('click', '.remove-header', function() {
            $(this).closest('.facility-header').remove();
        });

        $('#facilities-container').on('click', '.remove-item', function() {
            const container = $(this).closest('.items-container');
            $(this).closest('.d-flex').remove();
            updateItemNumbers(container);
        });

        function updateItemNumbers(container) {
            container.find('.item-number').each(function(index) {
                $(this).text((index + 1) + '.');
            });
        }

        // Submit button functionality
        $('#submit-btn').on('click', function() {
            // Validate minimum 3 images
            if (Dropzone.instances.length === 0 || Dropzone.instances[0].files.length < 3) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Please upload at least 3 images.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            const formData = new FormData();
            formData.append('is_published', 1); // Set is_published to 1
            appendFormData(formData);

            $.ajax({
                url: urls.products,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product published successfully!',
                        type: 'success',
                        delay: 5000
                    });
                    clearAllFields();
                    window.location.href = urls.index;
                },
                error: function(error) {
                    App.Toast.showToast({
                        title: 'Error',
                        message: error.responseJSON.message || 'An error occurred while publishing the product.',
                        type: 'danger',
                        delay: 5000
                    });
                }
            });
        });

        // Draft button functionality
        $('#draft-btn').on('click', function() {
            // Validate minimum 3 images
            if (Dropzone.instances.length === 0 || Dropzone.instances[0].files.length < 3) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Please upload at least 3 images.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            const formData = new FormData();
            formData.append('is_published', 0); // Set is_published to 0
            appendFormData(formData);

            $.ajax({
                url: urls.products,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product saved as draft successfully!',
                        type: 'success',
                        delay: 5000
                    });
                    clearAllFields();
                    window.location.href = urls.index;
                },
                error: function(error) {
                    App.Toast.showToast({
                        title: 'Error',
                        message: error.responseJSON.message || 'An error occurred while saving the draft.',
                        type: 'danger',
                        delay: 5000
                    });
                }
            });
        });

        // Helper function to append form data
        function appendFormData(formData) {
            formData.append('name', $('#name').val());
            formData.append('category', $('#category').val());
            formData.append('address', $('#address').val());
            formData.append('distance_to_kariadi', $('#distance_to_kariadi').val());
            formData.append('whatsapp', $('#whatsapp').val());
            formData.append('description', editor.getHtml());
            const facilities = [];
        $('#facilities-container .facility-header').each(function() {
            const header = $(this).find('.header-input').val();
            const items = $(this).find('.item-input').map(function() { return $(this).val(); }).get();
            facilities.push({header: header, items: items});
        });
        formData.append('facilities', JSON.stringify(facilities));
            formData.append('google_maps_link', $('#google_maps_link').val());
            formData.append('price', $('#price').val().replace(/\./g, '').replace(/\$/g, ''));
            formData.append('is_active', 1);
            formData.append('created_by', "{{ auth()->id() }}");
            formData.append('updated_by', "{{ auth()->id() }}");

            // Append images and collect their names
            const imageNames = [];
            if (Dropzone.instances.length > 0) {
                const dropzoneFiles = Dropzone.instances[0].files;
                dropzoneFiles.forEach((file, index) => {
                    formData.append(`file_image[${index}]`, file);
                    imageNames.push(file.name);
                });
            }
            formData.append('images', JSON.stringify(imageNames));
        }

        // Discard button
        $('#discard-btn').on('click', function() {
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure you want to discard changes?',
                theme: 'bootstrap',
                buttons: {
                    confirm: function () {
                        clearAllFields();
                        $('#responseMessage').html('');
                        App.Toast.showToast({
                            title: 'Success',
                            message: 'Changes discarded successfully!',
                            type: 'success',
                            delay: 5000
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        });

        // Helper to clear all fields
        function clearAllFields() {
            $('#name, #address, #distance_to_kariadi, #whatsapp, #google_maps_link, #price').val('');
            $('#facilities').val('[]');
            editor.setContent('');

            // Clear Dropzone files
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(instance => instance.removeAllFiles(true));
            }
        }
    });
</script>
@endsection