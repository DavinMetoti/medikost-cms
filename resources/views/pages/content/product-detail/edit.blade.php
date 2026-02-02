@extends('pages.content.index')

@section('main')
@php
$facilities = $productDetail->facilities;
if (is_string($facilities)) {
    $facilities = json_decode($facilities, true) ?? [];
}
$facilities = is_array($facilities) ? $facilities : [];
@endphp
<x-breadcrumb :items="[
        ['title' => 'Product Detail Management', 'url' => route('app.product-details.index')],
        ['title' => 'Edit', 'url' => '#']
    ]" />
<div class="row align-items-center justify-content-between g-3">
    <div class="col-auto">
        <h3>Edit Product Detail</h3>
        <p>Update the product detail.</p>
    </div>
    <div class="col-auto">
        <div class="row g-2 g-sm-3">
            <div class="col-auto"><button class="btn btn-phoenix-danger" id="discard-btn"><span class="fas fa-trash-alt me-2"></span>Discard</button></div>
            <div class="col-auto"><button class="btn btn-secondary" id="draft-btn"><span class="fas fa-folder-open me-2"></span>Save Draft</button></div>
            <div class="col-auto"><button class="btn btn-primary" id="submit-btn"><span class="fas fa-check me-2"></span>Publish Detail</button></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Detail Information</h5>
            </div>
            <div class="card-body">
                <div id="responseMessage"></div>

                {{-- Product --}}
                <div class="mb-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select class="form-select" id="product_id" name="product_id" required>
                        <option value="{{ $productDetail->product_id }}" selected>{{ $productDetail->product->name ?? 'Select Product' }}</option>
                    </select>
                </div>

                {{-- Room Name --}}
                <div class="mb-3">
                    <label for="room_name" class="form-label">Room Name</label>
                    <input class="form-control" type="text" id="room_name" name="room_name" placeholder="Room Name" value="{{ $productDetail->room_name }}" required>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input class="form-control currency" type="text" id="price" name="price" placeholder="Price" value="{{ number_format($productDetail->price, 0, ',', '.') }}" required>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="kosong" {{ $productDetail->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
                        <option value="isi" {{ $productDetail->status == 'isi' ? 'selected' : '' }}>Isi</option>
                    </select>
                </div>

                {{-- Available Rooms --}}
                <div class="mb-3">
                    <input type="hidden" id="available_rooms" name="available_rooms" value="1">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <div id="description" class="form-control" style="min-height: 200px;"></div>
                </div>

                {{-- Is Active --}}
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $productDetail->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>
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
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Spesifikasi tipe kamar">
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
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Fasilitas kamar">
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
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Fasilitas kamar mandi">
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
                                <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="Peraturan khusus tipe kamar">
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
                                    <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header" value="{{ $facility['header'] ?? '' }}">
                                </div>
                                <div class="items-container">
                                    @if(isset($facility['items']) && is_array($facility['items']))
                                        @foreach($facility['items'] as $item)
                                            <div class="facility-item mb-2 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" placeholder="Item" value="{{ $item }}">
                                                <button class="btn btn-danger btn-sm remove-item">Remove</button>
                                            </div>
                                        @endforeach
                                    @endif
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
                @if(!empty($productDetail->images))
                <div class="mb-3">
                    <h6>Existing Images</h6>
                    <div class="row" id="existing-images">
                        @php
                        $existingImages = $productDetail->images;
                        if (is_string($existingImages)) {
                            $existingImages = json_decode($existingImages, true) ?? [];
                        }
                        $existingImages = is_array($existingImages) ? $existingImages : [];
                        @endphp
                        @foreach($existingImages as $image)
                        <div class="col-md-4 mb-3 existing-image" data-image="{{ $image }}">
                            <div class="border p-2 rounded-2">
                                <img src="{{ asset('storage/product-details/' . $image) }}" alt="Existing Image" class="img-fluid rounded-2" style="max-height: 150px; object-fit: cover;" />
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
        productDetails: "{{ route('app.product-details.update', ['product_detail' => $productDetail->id]) }}",
        index: "{{ route('app.product-details.index') }}",
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
            editor.setContent({!! json_encode(html_entity_decode($productDetail->description ?? '')) !!});
        }, 500);

        // Initialize Select2 for Product
        const productSelect = new App.Select2Wrapper('#product_id', {
            ajax: '{{ route("app.products.search") }}',
            placeholder: 'Cari produk...',
            allowClear: true
        });

        // Set initial value for product select
        if ('{{ $productDetail->product_id }}') {
            productSelect.setValue('{{ $productDetail->product_id }}');
            // Add the selected option manually since AJAX doesn't preload
            const option = new Option('{{ $productDetail->product->name ?? "" }}', '{{ $productDetail->product_id }}', true, true);
            $('#product_id').append(option).trigger('change');
        }

        // Add header
        $('#add-header').on('click', function() {
            const headerHtml = `
                <div class="facility-header mb-3 border p-3 rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <input type="text" class="form-control fw-bold header-input bg-white" placeholder="Header">
                    </div>
                    <div class="items-container">
                        <!-- Items -->
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
            const itemHtml = `
                <div class="facility-item mb-2 d-flex align-items-center">
                    <input type="text" class="form-control me-2 item-input" placeholder="Item">
                    <button class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
            `;
            $(this).closest('.facility-header').find('.items-container').append(itemHtml);
        });

        $('#facilities-container').on('click', '.remove-item', function() {
            $(this).closest('.facility-item').remove();
        });

        $('#facilities-container').on('click', '.remove-header', function() {
            $(this).closest('.facility-header').remove();
        });

        // Handle removing existing images
        $('#existing-images').on('click', '.remove-existing', function() {
            const image = $(this).closest('.existing-image').data('image');
            removedImages.push(image);
            $(this).closest('.existing-image').remove();
        });

        // Submit button functionality
        $('#submit-btn').on('click', function () {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('is_active', 1);
            appendFormData(formData);

            $.ajax({
                url: urls.productDetails,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product Detail updated successfully!',
                        type: 'success',
                        delay: 5000
                    });
                    setTimeout(function () {
                        window.location.href = urls.index;
                    }, 2000);
                },
                error: function (error) {
                    App.Toast.showToast({
                        title: 'Error',
                        message: error.responseJSON.message || 'An error occurred.',
                        type: 'danger',
                        delay: 5000
                    });
                }
            });
        });

        // Draft button functionality
        $('#draft-btn').on('click', function () {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('is_active', 0);
            appendFormData(formData);

            $.ajax({
                url: urls.productDetails,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    App.Toast.showToast({
                        title: 'Success',
                        message: 'Product Detail saved as draft!',
                        type: 'success',
                        delay: 5000
                    });
                    setTimeout(function () {
                        window.location.href = urls.index;
                    }, 2000);
                },
                error: function (error) {
                    App.Toast.showToast({
                        title: 'Error',
                        message: error.responseJSON.message || 'An error occurred.',
                        type: 'danger',
                        delay: 5000
                    });
                }
            });
        });

        // Helper function to append form data
        function appendFormData(formData) {
            formData.append('product_id', $('#product_id').val());
            formData.append('room_name', $('#room_name').val());
            formData.append('price', $('#price').val().replace(/\./g, ''));
            formData.append('status', $('#status').val());
            formData.append('available_rooms', $('#available_rooms').val());
            formData.append('description', editor.getHtml());
            formData.append('is_active', $('#is_active').is(':checked') ? 1 : 0);

            // Append facilities
            const facilities = [];
            $('#facilities-container .facility-header').each(function() {
                const header = $(this).find('.header-input').val();
                const items = $(this).find('.item-input').map(function() { return $(this).val(); }).get().filter(item => item.trim() !== '');
                if (header.trim() !== '' || items.length > 0) {
                    facilities.push({header: header, items: items});
                }
            });
            formData.append('facilities', JSON.stringify(facilities));

            // Append removed images
            formData.append('removed_images', JSON.stringify(removedImages));

            // Append images
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