@extends('layouts.app')

@section('content')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.navbar')
    <div class="content">
        <div class="pb-5">
          <div class="row g-4">
            <div class="col-12">
                @yield('main')
            </div>
          </div>
        </div>
    </div>
@endsection
