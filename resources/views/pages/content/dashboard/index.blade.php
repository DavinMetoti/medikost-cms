@extends('pages.content.index')

@section('main')
<div class="">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-primary mb-2">Dashboard Kost Management</h3>
            <p class="text-muted mb-0">Selamat datang! Kelola kost dan kamar Anda dengan mudah.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5 g-3">
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-building text-primary"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $totalProducts }}</h2>
                    <small class="text-muted fw-medium">Total Kost</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $publishedProducts }}</h2>
                    <small class="text-muted fw-medium">Published</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-edit text-warning"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $draftProducts }}</h2>
                    <small class="text-muted fw-medium">Draft</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-door-open text-info"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $totalRooms }}</h2>
                    <small class="text-muted fw-medium">Total Kamar</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-bed text-success"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $availableRooms }}</h2>
                    <small class="text-muted fw-medium">Kamar Kosong</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100 stat-card text-center">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center stat-icon mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-check text-danger"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 fw-bold">{{ $occupiedRooms }}</h2>
                    <small class="text-muted fw-medium">Kamar Terisi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Shortcuts -->
    <div class="row mb-5 g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold text-primary">Distribusi Kategori Kost</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="categoryChart" style="max-width: 100%; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold text-primary">Shortcut Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <a href="{{ route('app.products.create') }}" class="btn btn-outline-primary btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span class="fw-bold">Tambah Kost</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('app.products.index') }}" class="btn btn-outline-secondary btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span class="fw-bold">Lihat Kost</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('app.product-details.create') }}" class="btn btn-outline-success btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span class="fw-bold">Tambah Kamar</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('app.product-details.index') }}" class="btn btn-outline-info btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span class="fw-bold">Lihat Kamar</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('user-management.users.index') }}" class="btn btn-outline-warning btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span class="fw-bold">Manage Users</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="btn btn-outline-dark btn-lg w-100 d-flex flex-column align-items-center justify-content-center shortcut-btn py-4">
                                <i class="fas fa-cog fa-2x mb-2"></i>
                                <span class="fw-bold">Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold text-primary">Kost Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProducts as $product)
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->address }}</small>
                                </div>
                                <a href="{{ route('app.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary ms-3">View</a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kost.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold text-primary">Kamar Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentRooms->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentRooms as $room)
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-bed text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $room->room_name }}</h6>
                                    <small class="text-muted">{{ $room->product->name ?? 'N/A' }}</small>
                                </div>
                                <a href="{{ route('app.product-details.show', $room->id) }}" class="btn btn-sm btn-outline-success ms-3">View</a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kamar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ secure_asset('vendors/chart/chart.umd.js') }}"></script>
<script>
$(document).ready(function() {
    const categoryData = @json($categoryStats);
    const labels = Object.keys(categoryData);
    const data = Object.values(categoryData);

    if (labels.length > 0) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#667eea', // Modern blue
                        '#764ba2', // Modern purple
                        '#f093fb'  // Modern pink
                    ],
                    borderColor: [
                        '#5a67d8',
                        '#6b46c1',
                        '#ed64a6'
                    ],
                    borderWidth: 2,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                cutout: '60%'
            }
        });
    } else {
        const chartContainer = document.querySelector('.card-body');
        chartContainer.innerHTML = '<div class="text-center py-5"><i class="fas fa-chart-pie fa-3x text-muted mb-3"></i><p class="text-muted">Belum ada data kategori.</p></div>';
    }

    // Add hover effects
    $('.stat-card').hover(
        function() { $(this).addClass('shadow-lg'); },
        function() { $(this).removeClass('shadow-lg'); }
    );

    $('.shortcut-btn').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
});
</script>
@endsection
