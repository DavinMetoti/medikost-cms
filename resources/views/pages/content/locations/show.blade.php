@extends('pages.content.index')

@section('main')
    <style>
        .location-tree {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .location-tree ul {
            list-style: none;
            padding-left: 25px;
            margin: 0;
        }
        .location-tree li {
            position: relative;
            padding: 8px 0;
        }
        .location-tree li::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: #007bff;
        }
        .location-tree li::after {
            content: '';
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 1px;
            background: #007bff;
        }
        .location-tree li:last-child::before {
            bottom: 50%;
        }
        .location-tree .tree-node {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: #333;
            margin-left: 10px;
        }
        .location-tree .tree-node:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #007bff;
        }
        .location-tree .tree-node i {
            margin-right: 8px;
            color: #007bff;
        }
        .location-tree ul ul {
            padding-left: 20px;
        }
        .location-tree ul ul li::before {
            left: -10px;
        }
        .location-tree ul ul li::after {
            left: -10px;
            width: 10px;
        }
    </style>
    <x-breadcrumb :items="[
        ['title' => 'Locations', 'url' => route('app.locations.index')],
        ['title' => 'Location Details', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Location Details</h3>
                    <p>View the details of the selected location.</p>
                </div>
                <a href="{{ route('app.locations.edit', $location->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Location
                </a>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Location Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Code</label>
                                    <p class="form-control-plaintext">{{ $location->code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Name</label>
                                    <p class="form-control-plaintext">{{ $location->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Warehouse</label>
                                    <p class="form-control-plaintext">
                                        @if($location->warehouse)
                                            {{ $location->warehouse->code }} - {{ $location->warehouse->name }}
                                        @else
                                            No Warehouse
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Location Type</label>
                                    <p class="form-control-plaintext">
                                        @if($location->locationType)
                                            {{ $location->locationType->code }} - {{ $location->locationType->name }}
                                        @else
                                            No Type
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Parent Location</label>
                                    <p class="form-control-plaintext">
                                        @if($location->parent)
                                            {{ $location->parent->code }} - {{ $location->parent->name }}
                                        @else
                                            No Parent
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $location->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $location->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="fw-bold">Description</label>
                                    <p class="form-control-plaintext">{{ $location->description ?: 'No description' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Location Hierarchy</h5>
                    </div>
                    <div class="card-body">
                        <div class="location-tree">
                            <ul>
                                @if($location->parent)
                                    <li>
                                        <a href="{{ route('app.locations.show', $location->parent->id) }}" class="tree-node">
                                            <i class="fas fa-map-marker-alt me-2"></i>{{ $location->parent->code }} - {{ $location->parent->name }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <span class="tree-node current-node">
                                        <i class="fas fa-map-marker me-2"></i>{{ $location->code }} - {{ $location->name }}
                                    </span>
                                </li>
                                @if($location->children->count() > 0)
                                    @foreach($location->children as $child)
                                        <li>
                                            <a href="{{ route('app.locations.show', $child->id) }}" class="tree-node">
                                                <i class="fas fa-map-marker-alt me-2"></i>{{ $child->code }} - {{ $child->name }}
                                            </a>
                                            @if($child->children->count() > 0)
                                                <ul>
                                                    @foreach($child->children as $grandchild)
                                                        <li>
                                                            <a href="{{ route('app.locations.show', $grandchild->id) }}" class="tree-node">
                                                                <i class="fas fa-map-marker-alt me-2"></i>{{ $grandchild->code }} - {{ $grandchild->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection