@extends('pages.content.index')

@section('main')
@php
$facilities = $product->facilities;
if (is_string($facilities)) {
    $facilities = json_decode($facilities, true) ?? [];
}
$facilities = is_array($facilities) ? $facilities : [];

$images = $product->images;
if (is_string($images)) {
    $images = json_decode($images, true) ?? [];
}
$images = is_array($images) ? $images : [];
@endphp
<x-breadcrumb :items="[
        ['title' => 'Product Management', 'url' => route('app.products.index')],
        ['title' => 'Detail', 'url' => '#']
    ]" />
<div class="row align-items-center justify-content-between g-3">
    <div class="col-auto">
        <h3>Product Detail</h3>
        <p>View the product details.</p>
    </div>
    <div class="col-auto">
        <div class="row g-2 g-sm-3">
            <div class="col-auto"><a href="{{ route('app.products.edit', $product->id) }}" class="btn btn-primary"><span class="fas fa-edit me-2"></span>Edit Product</a></div>
            <div class="col-auto"><a href="{{ route('app.products.index') }}" class="btn btn-secondary"><span class="fas fa-arrow-left me-2"></span>Back to List</a></div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Images</h5>
            </div>
            <div class="card-body">
                @if(empty($images))
                    <p>No images available.</p>
                @else
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <img id="main-image" src="{{ asset('storage/products/' . $images[0]) }}" alt="Main Image" class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <!-- image list -->
                        <div class="col-md-4 d-flex flex-column">
                            @if(count($images) > 1)
                            <div class="row" id="thumbnail-container">
                                @for($i = 0; $i < min(8, count($images)); $i++)
                                <div class="col-6 mb-2 thumbnail-wrapper" data-thumb-index="{{ $i }}">
                                    <img src="{{ asset('storage/products/' . $images[$i]) }}" alt="Thumbnail" class="img-fluid rounded thumbnail {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}" style="cursor: pointer; border: {{ $i == 0 ? '2px solid #007bff' : '1px solid #ddd' }};" />
                                </div>
                                @endfor
                            </div>
                            <div class="d-flex justify-content-center mt-auto">
                                <button class="btn btn-outline-secondary me-2" id="prev-image" disabled><i class="fas fa-chevron-left"></i></button>
                                <button class="btn btn-outline-secondary ms-2" id="next-image" {{ count($images) > 1 ? '' : 'disabled' }}><i class="fas fa-chevron-right"></i></button>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header pb-0">
                <h5>Kost Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Name</h6>
                        <p>{{ $product->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Category</h6>
                        <p>
                            @if($product->category == 'Putri')
                                <span class="badge bg-danger"><i class="fas fa-venus me-1"></i>Putri</span>
                            @elseif($product->category == 'Putra')
                                <span class="badge bg-primary"><i class="fas fa-mars me-1"></i>Putra</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-users me-1"></i>Campur</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Status</h6>
                        <p><span class="badge bg-{{ $product->is_published ? 'success' : 'warning' }}">{{ $product->is_published ? 'Published' : 'Draft' }}</span></p>
                    </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Address</h6>
                        <p>{{ $product->address }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Distance to Kariadi</h6>
                        <p>{{ $product->distance_to_kariadi }} km</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Whatsapp</h6>
                        <p>{{ $product->whatsapp }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Google Maps Link</h6>
                        <p><a href="{{ $product->google_maps_link }}" target="_blank">{{ $product->google_maps_link }}</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Description</h6>
                        <div>{!! $product->description !!}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="list-room">
            @if($product->productDetails->count() > 0)
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Room List</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($product->productDetails as $detail)
                            @php
                                $roomImages = $detail->images;
                                if (is_string($roomImages)) {
                                    $roomImages = json_decode($roomImages, true) ?? [];
                                }
                                $roomImages = is_array($roomImages) ? $roomImages : [];
                                $firstImage = !empty($roomImages) ? $roomImages[0] : null;

                                $roomFacilities = $detail->facilities;
                                if (is_string($roomFacilities)) {
                                    $roomFacilities = json_decode($roomFacilities, true) ?? [];
                                }
                                $roomFacilities = is_array($roomFacilities) ? $roomFacilities : [];
                                $allFacilities = collect($roomFacilities)->pluck('items')->flatten();
                                $firstThreeFacilities = $allFacilities->take(3);
                                $moreCount = $allFacilities->count() - 3;
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    @if($firstImage)
                                    <img src="{{ asset('storage/product-details/' . $firstImage) }}" class="card-img-top" alt="Room Image" style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">{{ $detail->room_name }}</h6>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h5 class="text-primary me-2 mb-0">Rp {{ number_format($detail->price, 0, ',', '.') }}</h5>
                                            <span class="badge bg-{{ $detail->status == 'kosong' ? 'success' : 'warning' }}">{{ ucfirst($detail->status) }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">{{ $detail->available_rooms ?? 'N/A' }} rooms available</small>
                                        </div>
                                        @if($firstThreeFacilities->count() > 0)
                                        <div class="mb-2">
                                            @foreach($firstThreeFacilities as $facility)
                                            <span class="badge bg-light text-dark me-1">{{ $facility }}</span>
                                            @endforeach
                                            @if($moreCount > 0)
                                            <span class="badge bg-secondary">+{{ $moreCount }} more</span>
                                            @endif
                                        </div>
                                        @endif
                                        <a href="{{ route('app.product-details.show', $detail->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">No rooms available for this kost.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Facilities</h5>
            </div>
            <div class="card-body">
                @if(empty($facilities))
                    <p>No facilities available.</p>
                @else
                    @foreach($facilities as $facility)
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">{{ $facility['header'] }}</h6>
                        <div class="row">
                            @foreach($facility['items'] as $item)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>{{ $item }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentIndex = 0;
    const images = @json($images);

    function updateImage(index) {
        $('#main-image').attr('src', '{{ asset('storage/products/') }}/' + images[index]);
        $('.thumbnail').css('border', '1px solid #ddd').removeClass('active');
        $('.thumbnail[data-index="' + index + '"]').css('border', '2px solid #007bff').addClass('active');
        $('#prev-image').prop('disabled', index === 0);
        $('#next-image').prop('disabled', index === images.length - 1);

        // Update thumbnails
        const start = Math.floor(index / 8) * 8;
        $('.thumbnail-wrapper').each(function(i) {
            const thumbIndex = start + i;
            if (thumbIndex < images.length) {
                $(this).show();
                const img = $(this).find('img');
                img.attr('src', '{{ asset('storage/products/') }}/' + images[thumbIndex]);
                img.attr('data-index', thumbIndex);
                if (thumbIndex === index) {
                    img.css('border', '2px solid #007bff').addClass('active');
                } else {
                    img.css('border', '1px solid #ddd').removeClass('active');
                }
            } else {
                $(this).hide();
            }
        });
    }

    $('.thumbnail').on('click', function() {
        const index = $(this).data('index');
        currentIndex = index;
        updateImage(currentIndex);
    });

    $('#prev-image').on('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateImage(currentIndex);
        }
    });

    $('#next-image').on('click', function() {
        if (currentIndex < images.length - 1) {
            currentIndex++;
            updateImage(currentIndex);
        }
    });
});
</script>
@endsection