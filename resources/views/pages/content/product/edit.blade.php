@extends('pages.content.index')

@section('main')
@php
$facilities = json_decode($product->facilities ?? '[]', true);
@endphp
<x-breadcrumb :items="[
        ['title' => 'Product Management', 'url' => route('app.products.index')],
        ['title' => 'Edit', 'url' => '#']
    ]" />
<div class="row align-items-center justify-content-between g-3">
    <div class="col-auto">
        <h3>Edit Product</h3>
        <p>Update the product details.</p>
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
                    <input class="form-control" type="text" id="name" name="name" placeholder="Your Product Name" value="{{ $product->name }}" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="Campur" {{ $product->category == 'Campur' ? 'selected' : '' }}>Campur</option>
                        <option value="Putri" {{ $product->category == 'Putri' ? 'selected' : '' }}>Putri</option>
                        <option value="Putra" {{ $product->category == 'Putra' ? 'selected' : '' }}>Putra</option>
                    </select>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Product Address" rows="3">{{ $product->address }}</textarea>
                </div>

                {{-- Distance to Kariadi --}}
                <div class="mb-3">
                    <label for="distance_to_kariadi" class="form-label">Distance to Kariadi (km)</label>
                    <input class="form-control" type="number" step="0.01" id="distance_to_kariadi" name="distance_to_kariadi" placeholder="Distance in km" value="{{ $product->distance_to_kariadi }}">
                </div>

                {{-- Whatsapp --}}
                <div class="mb-3">
                    <label for="whatsapp" class="form-label">Whatsapp</label>
                    <input class="form-control" type="text" id="whatsapp" name="whatsapp" placeholder="628xxxxxxxxx" value="{{ $product->whatsapp }}">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <div id="description" class="form-control" style="min-height: 200px;"></div>
                </div>

                {{-- Google Maps Link --}}
                <div class="mb-3">
                    <label for="google_maps_link" class="form-label">Google Maps Link</label>
                    <input class="form-control" type="url" id="google_maps_link" name="google_maps_link" placeholder="https://maps.google.com/..." value="{{ $product->google_maps_link }}">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <h5>Facilities</h5>
            </div>
            <div class="card-body">
                <div id="facilities-container">
                    @if(empty($facilities))
                        <!-- Default headers -->
                        <div class="facility-header mb-3 border p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Peraturan Kost">
                            </div>
                            <div class="items-container">
                                <!-- Items -->
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
                                <!-- Items -->
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
                                <!-- Items -->
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
                                <!-- Items -->
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                                <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                            </div>
                        </div>
                    @else
                        @foreach($facilities as $facility)
                        <div class="facility-header mb-3 border p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="{{ $facility['header'] }}">
                            </div>
                            <div class="items-container">
                                @foreach($facility['items'] as $index => $item)
                                <div class="d-flex align-items-center mb-1">
                                    <span class="item-number me-2">{{ $index + 1 }}.</span>
                                    <input type="text" class="form-control item-input" placeholder="Item" value="{{ $item }}">
                                    <button class="btn btn-danger btn-sm remove-item ms-2">Remove</button>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button class="btn btn-secondary btn-sm add-item">Add Item</button>
                                <button class="btn btn-danger btn-sm remove-header">Remove Header</button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" id="add-header" class="btn btn-primary mt-3">Add Header</button>
            </div>
        </div>
    </div>
    <div class="col-md-5">

        <div class="card">
            <div class="card-header pb-0">
                <h5>Image</h5>
            </div>
            <div class="card-body">
                @if(!empty($product->images))
                <div class="mb-3">
                    <h6>Existing Images</h6>
                    <div class="row" id="existing-images">
                        @foreach(json_decode($product->images, true) as $image)
                        <div class="col-md-4 mb-3 existing-image" data-image="{{ $image }}">
                            <div class="border p-2 rounded-2">
                                <img src="{{ asset('storage/products/' . $image) }}" alt="Existing Image" class="img-fluid rounded-2" style="max-height: 150px; object-fit: cover;" />
                                <div class="mt-2">
                                    <button class="btn btn-danger btn-sm w-100 remove-existing">Remove</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
        products: "{{ route('app.products.update', ['product' => $product->id]) }}",
        index: "{{ route('app.products.index') }}",
    };

    let editor = null;
    let removedImages = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // Initialize Summernote Editor
        if (editor) {
            editor.destroy();
        }
        editor = new App.SummernoteEditor('#description');
        setTimeout(() => {
            editor.setContent({!! json_encode(html_entity_decode($product->description ?? '')) !!});
        }, 500);

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

        // Handle removing existing images
        $('#existing-images').on('click', '.remove-existing', function() {
            const image = $(this).closest('.existing-image').data('image');
            removedImages.push(image);
            $(this).closest('.existing-image').remove();
        });

        // Submit button functionality
        $('#submit-btn').on('click', function () {
            // Validate WhatsApp number
            const whatsapp = $('#whatsapp').val();
            if (!whatsapp.startsWith('628')) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Nomor WhatsApp harus dimulai dengan 628.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            // Validate minimum 3 images
            const existingImages = @json(json_decode($product->images ?? '[]', true));
            const newImagesCount = Dropzone.instances.length > 0 ? Dropzone.instances[0].files.length : 0;
            const removedCount = removedImages.length;
            if (existingImages.length - removedCount + newImagesCount < 3) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Please ensure there are at least 3 images in total.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PUT'); // Explicitly set the method to PUT
            formData.append('is_published', 1); // Set is_published to 1
            appendFormData(formData);

            $.ajax({
                url: "{{ route('app.products.update', ['product' => $product->id]) }}",
                method: 'POST', // Use POST to send the PUT request
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product updated successfully!',
                        type: 'success',
                        delay: 5000
                    });
                    setTimeout(function () {
                        window.location.href = "{{ route('app.products.index') }}"; // Redirect after 2 seconds
                    }, 2000);
                },
                error: function (error) {
                    App.Toast.showToast({
                        title: 'Error',
                        message: error.responseJSON.message || 'An error occurred while updating the product.',
                        type: 'danger',
                        delay: 5000
                    });
                }
            });
        });

        // Draft button functionality
        $('#draft-btn').on('click', function () {
            // Validate WhatsApp number
            const whatsapp = $('#whatsapp').val();
            if (!whatsapp.startsWith('628')) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Nomor WhatsApp harus dimulai dengan 628.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            // Validate minimum 3 images
            const existingImages = @json(json_decode($product->images ?? '[]', true));
            const newImagesCount = Dropzone.instances.length > 0 ? Dropzone.instances[0].files.length : 0;
            const removedCount = removedImages.length;
            if (existingImages.length - removedCount + newImagesCount < 3) {
                App.Toast.showToast({
                    title: 'Error',
                    message: 'Please ensure there are at least 3 images in total.',
                    type: 'danger',
                    delay: 5000
                });
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PUT'); // Explicitly set the method to PUT
            formData.append('is_published', 0); // Set is_published to 0
            appendFormData(formData);

            $.ajax({
                url: "{{ route('app.products.update', ['product' => $product->id]) }}",
                method: 'POST', // Use POST to send the PUT request
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product saved as draft successfully!',
                        type: 'success',
                        delay: 5000
                    });
                    setTimeout(function () {
                        window.location.href = "{{ route('app.products.index') }}"; // Redirect after 2 seconds
                    }, 2000);
                },
                error: function (error) {
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
            formData.append('google_maps_link', $('#google_maps_link').val());
            formData.append('is_active', 1); // Always active
            formData.append('updated_by', "{{ auth()->id() }}");

            // Append facilities
            const facilities = [];
            $('#facilities-container .facility-header').each(function() {
                const header = $(this).find('.header-input').val();
                const items = $(this).find('.item-input').map(function() { return $(this).val(); }).get();
                facilities.push({header: header, items: items});
            });
            formData.append('facilities', JSON.stringify(facilities));

            // Append removed images
            formData.append('removed_images', JSON.stringify(removedImages));

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
    });
</script>
@endsection