@extends('pages.content.index')

@section('main')
    <x-breadcrumb :items="[
        ['title' => 'Category', 'url' => route('app.categories.index')],
        ['title' => 'Edit Category', 'url' => '#']
    ]" />

    <div class="card w-100">
        <div class="card-header pb-0">
            <h3>Edit Category</h3>
            <p>Update the form to edit the Category.</p>
        </div>
        <div class="card-body">
            <form id="category-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="name">Category Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ $category->name }}" placeholder="Type category name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the category" required>{{ $category->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="Unit">Unit</label>
                    <input class="form-control" type="text" id="unit" name="unit" value="{{ $category->unit }}" placeholder="Type category name" required>
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
            const categoryId = @json($category->id);
            let urls = {
                categories: "{{ route('app.categories.update', ['category' => '__CATEGORY_ID__']) }}".replace('__CATEGORY_ID__', categoryId),
                index: "{{ route('app.categories.index') }}",
            };
            $('#category-edit-form').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let formHandler = new App.Form(urls.categories, formData, this, e, { redirectUrl: urls.index });

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
