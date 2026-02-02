@extends('pages.content.index')

@section('main')
@php
$facilities = $productDetail->facilities;
if (is_string($facilities)) {
    $facilities = json_decode($facilities, true) ?? [];
}
$facilities = is_array($facilities) ? $facilities : [];

$images = $productDetail->images;
if (is_string($images)) {
    $images = json_decode($images, true) ?? [];
}
$images = is_array($images) ? $images : [];

$productFacilities = $productDetail->product->facilities ?? [];
if (is_string($productFacilities)) {
    $productFacilities = json_decode($productFacilities, true) ?? [];
}
$productFacilities = is_array($productFacilities) ? $productFacilities : [];
@endphp
<x-breadcrumb :items="[
        ['title' => 'Product Detail Management', 'url' => route('app.product-details.index')],
        ['title' => 'Detail', 'url' => '#']
    ]" />
<div class="row align-items-center justify-content-between g-3">
    <div class="col-auto">
        <h3>Product Detail</h3>
        <p>View the product detail.</p>
    </div>
    <div class="col-auto">
        <div class="row g-2 g-sm-3">
            <div class="col-auto"><a href="{{ route('app.product-details.edit', $productDetail->id) }}" class="btn btn-primary"><span class="fas fa-edit me-2"></span>Edit Detail</a></div>
            @if($productDetail->status == 'kosong' && $productDetail->available_rooms > 0)
            <div class="col-auto"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bookingModal"><span class="fas fa-calendar-check me-2"></span>Book Now</button></div>
            @endif
            <div class="col-auto"><a href="{{ route('app.product-details.index') }}" class="btn btn-secondary"><span class="fas fa-arrow-left me-2"></span>Back to List</a></div>
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
                            <img id="main-image" src="{{ asset('storage/product-details/' . $images[0]) }}" alt="Main Image" class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <!-- image list -->
                        <div class="col-md-4 d-flex flex-column">
                            @if(count($images) > 1)
                            <div class="row" id="thumbnail-container">
                                @for($i = 0; $i < min(8, count($images)); $i++)
                                <div class="col-6 mb-2 thumbnail-wrapper" data-thumb-index="{{ $i }}">
                                    <img src="{{ asset('storage/product-details/' . $images[$i]) }}" alt="Thumbnail" class="img-fluid rounded thumbnail {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}" style="cursor: pointer; border: {{ $i == 0 ? '2px solid #007bff' : '1px solid #ddd' }};" />
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
<div class="row d-flex align-items-stretch">
    <div class="col-md-8">
        <div class="card mb-3 h-100">
            <div class="card-header pb-0">
                <h5>Room Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Kost</h6>
                        <p>{{ $productDetail->product->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Room Name</h6>
                        <p>{{ $productDetail->room_name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Price</h6>
                        <p>Rp {{ number_format($productDetail->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Status</h6>
                        <p><span class="badge bg-{{ $productDetail->status == 'kosong' ? 'success' : 'warning' }}">{{ ucfirst($productDetail->status) }}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Jumlah Kamar Tersedia</h6>
                        <p>{{ $productDetail->available_rooms ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Active</h6>
                        <p><span class="badge bg-{{ $productDetail->is_active ? 'success' : 'danger' }}">{{ $productDetail->is_active ? 'Active' : 'Inactive' }}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Description</h6>
                        <div>{!! $productDetail->description !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h5>Room Facilities</h5>
            </div>
            <div class="card-body">
                @if(empty($facilities))
                    <p>No facilities available.</p>
                @else
                    @foreach($facilities as $facility)
                        @if(isset($facility['header']) && !empty($facility['header']))
                            <div class="mb-3">
                                <h6 class="fw-bold">{{ $facility['header'] }}</h6>
                                @if(isset($facility['items']) && is_array($facility['items']) && !empty($facility['items']))
                                    <ul class="list-unstyled">
                                        @foreach($facility['items'] as $item)
                                            <li class="mb-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>{{ $item }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row mt-4 d-flex align-items-stretch">
    <div class="col-8">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h5>Kost Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Kost Name</h6>
                        <p>{{ $productDetail->product->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Address</h6>
                        <p>{{ $productDetail->product->address ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Distance to Kariadi</h6>
                        <p>{{ $productDetail->product->distance_to_kariadi ?? 'N/A' }} km</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">WhatsApp</h6>
                        <p>{{ $productDetail->product->whatsapp ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Google Maps</h6>
                        <p><a href="{{ $productDetail->product->google_maps_link ?? '#' }}" target="_blank">{{ $productDetail->product->google_maps_link ? 'View on Maps' : 'N/A' }}</a></p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold">Status</h6>
                        <p><span class="badge bg-{{ $productDetail->product->is_published ? 'success' : 'warning' }}">{{ $productDetail->product->is_published ? 'Published' : 'Draft' }}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Description</h6>
                        <div>{!! $productDetail->product->description !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h5>Kost Facilities</h5>
            </div>
            <div class="card-body">
                @if(empty($productFacilities))
                    <p>No facilities available.</p>
                @else
                    @foreach($productFacilities as $facility)
                        @if(isset($facility['header']) && !empty($facility['header']))
                            <div class="mb-3">
                                <h6 class="fw-bold">{{ $facility['header'] }}</h6>
                                @if(isset($facility['items']) && is_array($facility['items']) && !empty($facility['items']))
                                    <ul class="list-unstyled">
                                        @foreach($facility['items'] as $item)
                                            <li class="mb-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>{{ $item }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Booking History</h5>
            </div>
            <div class="card-body">
                @if($bookings->isEmpty())
                    <p>No booking history for this room.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-striped fs-9 mb-0 custom-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>Check-in Date</th>
                                    <th>Check-out Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Booked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $index => $booking)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->check_in_date->format('d M Y') }}</td>
                                    <td>{{ $booking->check_out_date->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }}">{{ ucfirst($booking->status) }}</span></td>
                                    <td>{{ $booking->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        $('#main-image').attr('src', '{{ asset('storage/product-details/') }}/' + images[index]);
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
                img.attr('src', '{{ asset('storage/product-details/') }}/' + images[thumbIndex]);
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

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book Room: {{ $productDetail->room_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookingForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="check_in_date" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="check_out_date" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                    </div>
                    <input type="hidden" name="product_detail_id" value="{{ $productDetail->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Book Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '{{ route("app.bookings.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Booking successful!');
                $('#bookingModal').modal('hide');
                location.reload(); // Reload to update status
            },
            error: function(xhr, status, error) {
                let message = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('\n');
                } else if (xhr.responseText) {
                    message = 'Server error: ' + xhr.status + ' - ' + error;
                }
                alert(message);
            }
        });
    });
});
</script>
@endsection