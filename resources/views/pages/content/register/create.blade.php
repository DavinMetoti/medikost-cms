@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'User Management', 'url' => route('user-management.users.index')],
        ['title' => 'Register', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Register a New Account</h3>
            <p>Fill in the form to create a new account.</p>
        </div>
        <div class="card-body">
            <form id="user-register_registration_form">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your Full Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="role">Access Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>

                <div id="responseMessage"></div>
            </form>
        </div>
    </div>

    <script>
        let urls = {
            users : "{{ route('user-management.users.store') }}",
        }

        $(document).ready(function () {
            $('#user-register_registration_form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.users, formData, this, e);

                formHandler.sendRequest()
                    .then(response => {
                        console.log("Success:", response);
                    })
                    .catch(error => {
                        console.log("Error:", error);
                    });
            });
        });
    </script>
@endsection
