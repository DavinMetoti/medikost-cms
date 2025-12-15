@extends('pages.content.index')

@section('main')
<x-breadcrumb :items="[
        ['title' => 'Product Management', 'url' => route('app.products.index')],
        ['title' => 'Edit', 'url' => '#']
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
                @if($categories->isEmpty() || $suppliers->isEmpty())
                <div class="alert alert-outline-danger d-flex align-items-center" role="alert">
                    <span class="fas fa-times-circle text-danger fs-5 me-3"></span>
                    <p class="mb-0 flex-1">No categories or suppliers found. Please create a category or supplier first.</p>
                </div>
                @else
                <div id="responseMessage"></div>

                <form id="product-form" method="POST" action="{{ route('app.products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Use PUT method for updating -->
                    {{-- Product Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="Your Product Name" required>
                    </div>

                    {{-- SKU and Barcode --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="sku" name="sku" placeholder="Generated SKU" disabled required>
                                <button type="button" class="btn btn-primary" id="refresh-sku">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input class="form-control" type="text" id="barcode" name="barcode" placeholder="Product Barcode" required>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <div id="description" class="form-control" style="min-height: 200px;"></div>
                    </div>
                </form>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <h5>Specification</h5>
            </div>
            <div class="card-body">
                <div id="spec-container"></div>
                <button type="button" id="add-spec" class="btn btn-primary mb-3">Add Specification</button>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Organizer</h5>
            </div>
            <div class="card-body">
                {{-- Categories and Suppliers --}}
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required></select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier" name="supplier" required></select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <div class="input-group">
                            <input class="form-control" type="text" id="tags" placeholder="Add a tag">
                            <button type="button" class="btn btn-primary" id="add-tag">Add</button>
                        </div>
                        <div id="tag-container" class="mt-2">
                            <!-- Tags will appear here as badges -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Inventory</h5>
            </div>
            <div class="card-body">
                <div class="row g-0 border-top border-bottom">
                    <div class="col-sm-4">
                        <div class="nav flex-sm-column border-bottom border-bottom-sm-0 border-end-sm fs-9 vertical-tab h-100 justify-content-between" role="tablist" aria-orientation="vertical">
                            <a class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center active" id="pricingTab" data-bs-toggle="tab" data-bs-target="#pricingTabContent" role="tab" aria-controls="pricingTabContent" aria-selected="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag me-sm-2 fs-4 nav-icons">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                <span class="d-none d-sm-inline">Pricing</span>
                            </a>
                            <a class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center" id="restockTab" data-bs-toggle="tab" data-bs-target="#restockTabContent" role="tab" aria-controls="restockTabContent" aria-selected="false" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package me-sm-2 fs-4 nav-icons">
                                    <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span class="d-none d-sm-inline">Restock</span>
                            </a>
                            <a class="nav-link text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center" id="advancedTab" data-bs-toggle="tab" data-bs-target="#advancedTabContent" role="tab" aria-controls="advancedTabContent" aria-selected="false" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock me-sm-2 fs-4 nav-icons">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span class="d-none d-sm-inline">Advanced</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="tab-content py-3 ps-sm-4 h-100">
                            <div class="tab-pane fade active show" id="pricingTabContent" role="tabpanel" aria-labelledby="pricingTab">
                                <h4 class="mb-3 d-sm-none">Pricing</h4>
                                {{-- Price --}}
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input class="form-control currency" type="text" id="price" name="price" placeholder="Product Price" required>
                                </div>
                                {{-- Purchase Price --}}
                                <div class="mb-3">
                                    <label for="purchase_price" class="form-label">Purchase Price</label>
                                    <input class="form-control currency" type="text" id="purchase_price" name="purchase_price" placeholder="Product purchase price" required>
                                </div>
                            </div>
                            <div class="tab-pane fade h-100" id="restockTabContent" role="tabpanel" aria-labelledby="restockTab">
                                <div class="d-flex flex-column h-100">
                                    <h5 class="mb-3 text-body-highlight">Add to Stock</h5>
                                    {{-- Unit --}}
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <select class="form-select" id="unit" name="unit_id" required></select>
                                    </div>


                                    {{-- Stock and Stock Minimum --}}
                                    <div id="stock-warning" class="alert alert-outline-warning d-flex align-items-center py-2" role="alert">
                                        <span class="fas fa-times-circle text-warning fs-5 me-3"></span>
                                        <p class="mb-0 flex-1">Please select the unit first.</p>
                                    </div>
                                    <div id="stock-group" class="row d-none">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" id="stock" name="stock" placeholder="Stock Quantity" min="0">
                                                <span class="input-group-text" id="stock-unit"></span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="stock_minimum" class="form-label">Stock Minimum</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" id="stock_minimum" name="stock_minimum" placeholder="Minimum Stock Quantity" min="0">
                                                <span class="input-group-text" id="stock-minimum-unit"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade h-100" id="shippingTabContent" role="tabpanel" aria-labelledby="shippingTab">
                                <div class="d-flex flex-column h-100">
                                    <h5 class="mb-3 text-body-highlight">Shipping Type</h5>
                                    <div class="flex-1">
                                        <div class="mb-4">
                                            <div class="form-check mb-1"><input class="form-check-input" type="radio" name="shippingRadio" id="fullfilledBySeller"><label class="form-check-label fs-8 text-body" for="fullfilledBySeller">Fullfilled by Seller</label></div>
                                            <div class="ps-4">
                                                <p class="text-body-secondary fs-9 mb-0">You’ll be responsible for product delivery. <br>Any damage or delay during shipping may cost you a Damage fee.</p>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check mb-1"><input class="form-check-input" type="radio" name="shippingRadio" id="fullfilledByPhoenix" checked="checked"><label class="form-check-label fs-8 text-body d-flex align-items-center" for="fullfilledByPhoenix">Fullfilled by Phoenix <span class="badge badge-phoenix badge-phoenix-warning fs-10 ms-2">Recommended</span></label></div>
                                            <div class="ps-4">
                                                <p class="text-body-secondary fs-9 mb-0">Your product, Our responsibility.<br>For a measly fee, we will handle the delivery process for you.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="fs-9 fw-semibold mb-0">See our <a class="fw-bold" href="add-product.html#!">Delivery terms and conditions </a>for details.</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="productsTabContent" role="tabpanel" aria-labelledby="productsTab">
                                <h5 class="mb-3 text-body-highlight">Global Delivery</h5>
                                <div class="mb-3">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="deliveryRadio" id="worldwideDelivery"><label class="form-check-label fs-8 text-body" for="worldwideDelivery">Worldwide delivery</label></div>
                                    <div class="ps-4">
                                        <p class="fs-9 mb-0 text-body-secondary">Only available with Shipping method: <a href="add-product.html#!">Fullfilled by Phoenix</a></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="deliveryRadio" checked="checked" id="selectedCountry"><label class="form-check-label fs-8 text-body" for="selectedCountry">Selected Countries</label></div>
                                    <div class="ps-4" style="max-width: 350px;">
                                        <div class="choices" data-type="select-multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false">
                                            <div class="choices__inner"><select class="form-select ps-4 choices__input" id="organizerMultiple" data-choices="data-choices" multiple="multiple" data-options="{&quot;removeItemButton&quot;:true,&quot;placeholder&quot;:true}" hidden="" tabindex="-1" data-choice="active"></select>
                                                <div class="choices__list choices__list--multiple"></div><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Type Country name" placeholder="Type Country name" style="min-width: 18ch; width: 1ch;">
                                            </div>
                                            <div class="choices__list choices__list--dropdown" aria-expanded="false">
                                                <div class="choices__list" aria-multiselectable="true" role="listbox">
                                                    <div id="choices--organizerMultiple-item-choice-1" class="choices__item choices__item--choice choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="Canada" data-select-text="" data-choice-selectable="" aria-selected="true">Canada</div>
                                                    <div id="choices--organizerMultiple-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Mexico" data-select-text="" data-choice-selectable="">Mexico</div>
                                                    <div id="choices--organizerMultiple-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="United Kingdom" data-select-text="" data-choice-selectable="">United Kingdom</div>
                                                    <div id="choices--organizerMultiple-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="United States of America" data-select-text="" data-choice-selectable="">United States of America</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="deliveryRadio" id="localDelivery"><label class="form-check-label fs-8 text-body" for="localDelivery">Local delivery</label></div>
                                    <p class="fs-9 ms-4 mb-0 text-body-secondary">Deliver to your country of residence <a href="add-product.html#!">Change profile address </a></p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="attributesTabContent" role="tabpanel" aria-labelledby="attributesTab">
                                <h5 class="mb-3 text-body-highlight">Attributes</h5>
                                <div class="form-check"><input class="form-check-input" id="fragileCheck" type="checkbox"><label class="form-check-label text-body fs-8" for="fragileCheck">Fragile Product</label></div>
                                <div class="form-check"><input class="form-check-input" id="biodegradableCheck" type="checkbox"><label class="form-check-label text-body fs-8" for="biodegradableCheck">Biodegradable</label></div>
                                <div class="mb-3">
                                    <div class="form-check"><input class="form-check-input" id="frozenCheck" type="checkbox" checked="checked"><label class="form-check-label text-body fs-8" for="frozenCheck">Frozen Product</label><input class="form-control" type="text" placeholder="Max. allowed Temperature" style="max-width: 350px;"></div>
                                </div>
                                <div class="form-check"><input class="form-check-input" id="productCheck" type="checkbox" checked="checked"><label class="form-check-label text-body fs-8" for="productCheck">Expiry Date of Product</label><input class="form-control inventory-attributes datetimepicker flatpickr-input" id="inventory" type="text" style="max-width: 350px;" placeholder="d/m/y" data-options="{&quot;disableMobile&quot;:true}" readonly="readonly"></div>
                            </div>
                            <div class="tab-pane fade" id="advancedTabContent" role="tabpanel" aria-labelledby="advancedTab">
                                <h5 class="mb-3 text-body-highlight">Advanced</h5>
                                <div class="row g-3">
                                    <div class="col-12 col-lg-6">
                                        <h5 class="mb-2 text-body-highlight">Product ID Type</h5><select class="form-select" aria-label="form-select-lg example">
                                            <option selected="selected">ISBN</option>
                                            <option value="1">UPC</option>
                                            <option value="2">EAN</option>
                                            <option value="3">JAN</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <h5 class="mb-2 text-body-highlight">Product ID</h5><input class="form-control" type="text" placeholder="ISBN Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        products: "{{ route('app.products.update', ['product' => $product->id]) }}", // Ensure the correct update route
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

    $(document).ready(function () {
        // Initialize Select2
        initSelect2('#category', urls.categorySelect);
        initSelect2('#supplier', urls.supplierSelect);
        initSelect2('#unit', urls.uomSelect);

        // Initialize Summernote Editor
        if (editor) {
            editor.destroy();
        }
        editor = new App.SummernoteEditor('#description');

        // Generate initial SKU
        $('#sku').val(generateSku());

        // Refresh SKU on button click
        $('#refresh-sku').on('click', function () {
            $('#sku').val(generateSku());
        });

        // Show/Hide Stock Inputs and Update Unit Labels Based on Unit Selection
        $('#unit').on('change', function () {
            const unit = $(this).val();
            if (unit) {
                $('#stock-warning').addClass('d-none');
                $('#stock-group').removeClass('d-none');
                $('#stock-unit').text('/ ' + unit);
                $('#stock-minimum-unit').text('/ ' + unit);
            } else {
                $('#stock-warning').removeClass('d-none');
                $('#stock-group').addClass('d-none');
                $('#stock-unit').text('');
                $('#stock-minimum-unit').text('');
            }
        });

        // Add tags functionality
        $('#add-tag').on('click', function () {
            const tagInput = $('#tags');
            const tagValue = tagInput.val().trim();

            if (tagValue) {
                const tagBadge = `
                        <span class="badge bg-primary me-2">
                            #${tagValue}
                            <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove" onclick="$(this).parent().remove()"></button>
                        </span>
                    `;
                $('#tag-container').append(tagBadge);
                tagInput.val('');
            }
        });

        // Add specifications functionality
        const specifications = [];

        $('#add-spec').on('click', function () {
            const specBlock = `
                <div class="spec-block mb-4 border p-4 rounded bg-light position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-spec" aria-label="Remove"></button>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Specification Title</label>
                            <input type="text" class="form-control" placeholder="Enter specification title (e.g., Processor)" name="title">
                        </div>
                    </div>
                    <div class="details-container">
                        <div class="row detail-row mb-3 align-items-center">
                            <div class="col-md-5">
                                <label for="name" class="form-label">Detail Name</label>
                                <input type="text" class="form-control" placeholder="Enter detail name (e.g., Model)" name="name">
                            </div>
                            <div class="col-md-5">
                                <label for="value" class="form-label">Detail Value</label>
                                <input type="text" class="form-control" placeholder="Enter detail value (e.g., Intel Core i7)" name="value">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-danger btn-sm remove-detail mt-4" aria-label="Remove Detail">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm add-detail">Add Detail</button>
                </div>
            `;
            $('#spec-container').append(specBlock);
        });

        $('#spec-container').on('click', '.add-detail', function () {
            const detailsContainer = $(this).siblings('.details-container');
            const detailRow = `
                <div class="row detail-row mb-3 align-items-center">
                    <div class="col-md-5">
                        <label for="name" class="form-label">Detail Name</label>
                        <input type="text" class="form-control" placeholder="Enter detail name (e.g., Model)" name="name">
                    </div>
                    <div class="col-md-5">
                        <label for="value" class="form-label">Detail Value</label>
                        <input type="text" class="form-control" placeholder="Enter detail value (e.g., Intel Core i7)" name="value">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-danger btn-sm remove-detail mt-4" aria-label="Remove Detail">Remove</button>
                    </div>
                </div>
            `;
            detailsContainer.append(detailRow);
        });

        $('#spec-container').on('click', '.remove-detail', function () {
            $(this).closest('.detail-row').remove();
        });

        $('#spec-container').on('click', '.remove-spec', function () {
            $(this).closest('.spec-block').remove();
        });

        // Check if Dropzone is already initialized
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(instance => instance.destroy()); // Destroy existing instances
        }

        // Initialize Dropzone
        const dropzone = new Dropzone("#dropzone-multiple", {
            url: "#", // No actual upload URL since this is for preview
            previewsContainer: ".dz-preview", // Ensure the preview container is correctly set
            init: function () {
                const existingImages = @json(json_decode($product->images ?? '[]', true));
            },
            error: function (file, message) {
                console.error("Dropzone error:", message);
            }
        });

        // Ensure the Dropzone clickable area is set correctly
        Dropzone.options.dropzoneMultiple = {
            clickable: "#dropzone-multiple",
        };

        // Submit button functionality
        $('#submit-btn').on('click', function () {
            const formData = new FormData();
            formData.append('_method', 'PUT'); // Explicitly set the method to PUT
            formData.append('is_published', 1); // Set is_published to 1
            appendFormData(formData);

            $.ajax({
                url: urls.products,
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
                    clearAllFields();
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
            const formData = new FormData();
            formData.append('_method', 'PUT'); // Explicitly set the method to PUT
            formData.append('is_published', 0); // Set is_published to 0
            appendFormData(formData);

            $.ajax({
                url: urls.products,
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
                    clearAllFields();
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
            formData.append('sku', $('#sku').val());
            formData.append('barcode', $('#barcode').val());
            formData.append('description', editor.getHtml());
            formData.append('category_id', $('#category').val());
            formData.append('supplier_id', $('#supplier').val());
            formData.append('price', $('#price').val().replace(/\./g, ''));
            formData.append('purchase_price', $('#purchase_price').val().replace(/\./g, ''));
            formData.append('unit_id', $('#unit').val());
            formData.append('stock', $('#stock').val());
            formData.append('stock_minimum', $('#stock_minimum').val());
            formData.append('created_by', "{{ auth()->id() }}"); // Add created_by
            formData.append('updated_by', "{{ auth()->id() }}"); // Add updated_by

            // Append tags as JSON
            const tags = Array.from($('#tag-container .badge')).map(tag => $(tag).text().trim().replace('#', ''));
            formData.append('tag', JSON.stringify(tags)); // Send tags as JSON array

            // Append specifications
            const specifications = Array.from($('#spec-container .spec-block')).map(spec => ({
                title: $(spec).find('input[name="title"]').val(),
                details: Array.from($(spec).find('.detail-row')).map(detail => ({
                    name: $(detail).find('input[name="name"]').val(),
                    value: $(detail).find('input[name="value"]').val()
                }))
            }));
            formData.append('specifications', JSON.stringify(specifications));

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
        $('#discard-btn').on('click', function () {
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
            $('#name, #barcode, #price, #purchase_price, #stock, #stock_minimum').val('');
            $('#tag-container').empty();
            $('#spec-container').empty();
            $('#category, #supplier, #unit').val(null).trigger('change');
            editor.setContent('');
            $('#sku').val(generateSku());

            // Clear Dropzone files
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(instance => instance.removeAllFiles(true));
            }
        }

        // Helpers
        function generateSku() {
            return 'SKU-' + Math.random().toString(36).substr(2, 8).toUpperCase();
        }

        function initSelect2(selector, apiUrl) {
            new App.Select2Wrapper(selector, {
                ajax: apiUrl,
                placeholder: 'Silakan pilih...',
            });
        }

        // Pre-fill form inputs with product data
        const product = @json($product);

        console.log(product);

        $('#name').val(product.name);
        $('#sku').val(product.sku).prop('disabled', true); // SKU is not editable
        $('#barcode').val(product.barcode);
        editor = new App.SummernoteEditor('#description');
        editor.setContent(product.description || '');

        // Set selected category and supplier
        $('#category').append(new Option(product.category.name, product.category_id, true, true)).trigger('change');
        $('#supplier').append(new Option(product.supplier.name, product.supplier_id, true, true)).trigger('change');

        $('#price').val(product.price);
        $('#purchase_price').val(product.purchase_price);
        $('#unit').val(product.unit_id).trigger('change');
        $('#stock').val(product.stock);
        $('#stock_minimum').val(product.stock_minimum);

        // Populate tags
        if (product.tag) {
            const tags = JSON.parse(product.tag);
            tags.forEach(tag => {
                const tagBadge = `
                    <span class="badge bg-primary me-2">
                        #${tag}
                        <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove" onclick="$(this).parent().remove()"></button>
                    </span>
                `;
                $('#tag-container').append(tagBadge);
            });
        }

        // Populate specifications
        if (product.specifications) {
            const specifications = JSON.parse(product.specifications);
            specifications.forEach(spec => {
                const specBlock = `
                    <div class="spec-block mb-4 border p-4 rounded bg-light position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-spec" aria-label="Remove"></button>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Specification Title</label>
                                <input type="text" class="form-control" placeholder="Enter specification title" name="title" value="${spec.title}">
                            </div>
                        </div>
                        <div class="details-container">
                            ${spec.details.map(detail => `
                                <div class="row detail-row mb-3 align-items-center">
                                    <div class="col-md-5">
                                        <label for="name" class="form-label">Detail Name</label>
                                        <input type="text" class="form-control" placeholder="Enter detail name" name="name" value="${detail.name}">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="value" class="form-label">Detail Value</label>
                                        <input type="text" class="form-control" placeholder="Enter detail value" name="value" value="${detail.value}">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-detail mt-4" aria-label="Remove Detail">Remove</button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-detail">Add Detail</button>
                    </div>
                `;
                $('#spec-container').append(specBlock);
            });
        }

        // Preview existing images
        if (product.images) {
            console.log(product.images);

            const images = JSON.parse(product.images);
            images.forEach(image => {
                const imagePreview = `
                    <div class="d-flex mb-3 pb-3 border-bottom border-translucent media">
                        <div class="border p-2 rounded-2 me-2">
                            <img class="rounded-2 dz-image" src="{{ secure_asset('storage/products') }}/${image}" alt="Product Image" />
                        </div>
                        <div class="flex-1 d-flex flex-between-center">
                            <div>
                                <h6>${image}</h6>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-body-tertiary btn-sm dropdown-toggle btn-reveal dropdown-caret-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border border-translucent py-2">
                                    <a class="dropdown-item btn-remove-image" href="#" data-image="${image}">Remove File</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('.dz-preview').append(imagePreview);
            });
        }

        // Disable inputs that are not editable
        $('#sku').prop('disabled', true);

        // Submit button functionality
        $('#submit-btn').on('click', function () {
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
            formData.append('sku', $('#sku').val());
            formData.append('barcode', $('#barcode').val());
            formData.append('description', editor.getHtml());
            formData.append('category_id', $('#category').val());
            formData.append('supplier_id', $('#supplier').val());
            formData.append('price', $('#price').val().replace(/\./g, ''));
            formData.append('purchase_price', $('#purchase_price').val().replace(/\./g, ''));
            formData.append('unit_id', $('#unit').val());
            formData.append('stock', $('#stock').val());
            formData.append('stock_minimum', $('#stock_minimum').val());
            formData.append('updated_by', "{{ auth()->id() }}"); // Add updated_by

            // Append tags as JSON
            const tags = Array.from($('#tag-container .badge')).map(tag => $(tag).text().trim().replace('#', ''));
            formData.append('tag', JSON.stringify(tags)); // Send tags as JSON array

            // Append specifications
            const specifications = Array.from($('#spec-container .spec-block')).map(spec => ({
                title: $(spec).find('input[name="title"]').val(),
                details: Array.from($(spec).find('.detail-row')).map(detail => ({
                    name: $(detail).find('input[name="name"]').val(),
                    value: $(detail).find('input[name="value"]').val()
                }))
            }));
            formData.append('specifications', JSON.stringify(specifications));

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