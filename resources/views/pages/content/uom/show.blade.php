@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Units of Measurement', 'url' => route('app.uom.index')],
        ['title' => 'View UoM', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Unit of Measurement Details</h3>
            <p>Details of the selected unit of measurement.</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <p>{{ $uom->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Abbreviation</label>
                        <p>{{ $uom->abbreviation }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type</label>
                        <p>{{ $uom->type }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Active</label>
                        <p>
                            @if($uom->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('app.uom.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('app.uom.edit', $uom) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
