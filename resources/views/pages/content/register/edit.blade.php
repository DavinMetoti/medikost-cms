@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'User Management', 'url' => route('user-management.users.index')],
        ['title' => 'Edit User', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Edit User Account</h3>
            <p>Update the form to edit the user account.</p>
        </div>
        <div class="card-body">
            <form id="user-register_registration_form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" placeholder="Your Full Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ $user->email }}" placeholder="name@example.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="role">Access Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

                <div id="responseMessage"></div>
            </form>
        </div>
    </div>

    <script>

        $(document).ready(function () {
            const userId = @json($user->id);
            let urls = {
                users: "{{ route('user-management.users.update', ['user' => '__USER_ID__']) }}".replace('__USER_ID__', userId)
            };
            $('#user-register_registration_form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.users, formData, this, e);

                formHandler.sendRequest()
                    .then(response => {
                        console.log("Success:", response);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.log("Error:", error);
                    });
            });
        });
    </script>
@endsection
