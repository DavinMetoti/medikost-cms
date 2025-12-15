@extends('pages.content.index')

@section('main')
    <style>
        .warehouse-tree {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .warehouse-tree ul {
            list-style: none;
            padding-left: 25px;
            margin: 0;
        }
        .warehouse-tree li {
            position: relative;
            padding: 8px 0;
        }
        .warehouse-tree li::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: #007bff;
        }
        .warehouse-tree li::after {
            content: '';
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 1px;
            background: #007bff;
        }
        .warehouse-tree li:last-child::before {
            bottom: 50%;
        }
        .warehouse-tree .tree-node {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: #333;
            margin-left: 10px;
        }
        .warehouse-tree .tree-node:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #007bff;
        }
        .warehouse-tree .tree-node i {
            margin-right: 8px;
            color: #007bff;
        }
        .warehouse-tree ul ul {
            padding-left: 20px;
        }
        .warehouse-tree ul ul li::before {
            left: -10px;
        }
        .warehouse-tree ul ul li::after {
            left: -10px;
            width: 10px;
        }
    </style>
    <x-breadcrumb :items="[
        ['title' => 'Warehouses', 'url' => route('app.warehouses.index')],
        ['title' => 'Warehouse Details', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Warehouse Details</h3>
                    <p>View the details of the selected warehouse.</p>
                </div>
                <a href="{{ route('app.warehouses.edit', $warehouse->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Warehouse
                </a>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Warehouse Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Code</label>
                                    <p class="form-control-plaintext">{{ $warehouse->code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Name</label>
                                    <p class="form-control-plaintext">{{ $warehouse->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="fw-bold">Address</label>
                                    <p class="form-control-plaintext">{{ $warehouse->address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="fw-bold">Description</label>
                                    <p class="form-control-plaintext">{{ $warehouse->description }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="fw-bold">Parent Warehouse</label>
                                    <p class="form-control-plaintext">
                                        @if($warehouse->parent)
                                            {{ $warehouse->parent->code }} - {{ $warehouse->parent->name }}
                                        @else
                                            No Parent
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Warehouse Hierarchy</h5>
                    </div>
                    <div class="card-body">
                        <div class="warehouse-tree">
                            <ul>
                                @if($warehouse->parent)
                                    <li>
                                        <a href="{{ route('app.warehouses.show', $warehouse->parent->id) }}" class="tree-node">
                                            <i class="fas fa-building me-2"></i>{{ $warehouse->parent->code }} - {{ $warehouse->parent->name }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <span class="tree-node current-node">
                                        <i class="fas fa-warehouse me-2"></i>{{ $warehouse->code }} - {{ $warehouse->name }}
                                    </span>
                                </li>
                                @if($warehouse->children->count() > 0)
                                    @foreach($warehouse->children as $child)
                                        <li>
                                            <a href="{{ route('app.warehouses.show', $child->id) }}" class="tree-node">
                                                <i class="fas fa-building me-2"></i>{{ $child->code }} - {{ $child->name }}
                                            </a>
                                            @if($child->children->count() > 0)
                                                <ul>
                                                    @foreach($child->children as $grandchild)
                                                        <li>
                                                            <a href="{{ route('app.warehouses.show', $grandchild->id) }}" class="tree-node">
                                                                <i class="fas fa-building me-2"></i>{{ $grandchild->code }} - {{ $grandchild->name }}
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
