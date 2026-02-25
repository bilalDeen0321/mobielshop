@extends('admin.layout')

@section('title', 'Categories')

@section('content')
<h3 class="page-title"> Categories
    <small>list & manage</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Categories</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-folder font-dark"></i>
                    <span class="caption-subject bold uppercase">Categories</span>
                </div>
                <div class="actions">
                    <button type="button" class="btn btn-sm green" id="btn-add-category"> Add New
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Name </th>
                                <th> Slug </th>
                                <th> Products </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td> {{ $category->id }} </td>
                                <td> {{ $category->name }} </td>
                                <td> {{ $category->slug }} </td>
                                <td> {{ $category->products_count }} </td>
                                <td>
                                    <button type="button" class="btn btn-xs blue btn-edit-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ e($category->name) }}"
                                        data-slug="{{ e($category->slug) }}"> Edit </button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs red" {{ $category->products_count > 0 ? 'disabled' : '' }}> Delete </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No categories yet. <button type="button" class="btn btn-link p-0" id="btn-add-category-empty">Add one</button>.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal (Add / Edit) -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="categoryModalLabel">Add Category</h4>
            </div>
            <form id="category-form">
                <div class="modal-body">
                    <div id="category-modal-errors" class="alert alert-danger" style="display:none;">
                        <ul class="mb-0 list-unstyled" id="category-modal-errors-list"></ul>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="category-form-method" value="POST">
                    <div class="form-group">
                        <label for="category-name">Name <span class="required">*</span></label>
                        <input type="text" id="category-name" name="name" class="form-control" required placeholder="Category name">
                    </div>
                    <div class="form-group">
                        <label for="category-slug">Slug</label>
                        <input type="text" id="category-slug" name="slug" class="form-control" placeholder="Leave blank to auto-generate from name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn green" id="category-form-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var modal = $('#categoryModal');
    var form = $('#category-form');
    var formMethod = $('#category-form-method');
    var errorsDiv = $('#category-modal-errors');
    var errorsList = $('#category-modal-errors-list');
    var storeUrl = '{{ route("admin.categories.store") }}';

    function openModalForAdd() {
        $('#categoryModalLabel').text('Add Category');
        form.attr('action', storeUrl);
        formMethod.val('POST');
        form.find('#category-name').val('');
        form.find('#category-slug').val('');
        errorsDiv.hide();
        modal.modal('show');
    }

    function openModalForEdit(id, name, slug) {
        $('#categoryModalLabel').text('Edit Category');
        form.attr('action', '{{ url("admin-panel/categories") }}/' + id);
        formMethod.val('PUT');
        form.find('#category-name').val(name);
        form.find('#category-slug').val(slug || '');
        errorsDiv.hide();
        modal.modal('show');
    }

    $('#btn-add-category, #btn-add-category-empty').on('click', function() {
        openModalForAdd();
    });

    $(document).on('click', '.btn-edit-category', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var slug = $(this).data('slug');
        openModalForEdit(id, name, slug);
    });

    form.on('submit', function(e) {
        e.preventDefault();
        var submitBtn = $('#category-form-submit');
        var url = form.attr('action');
        var method = formMethod.val();
        var formData = new FormData(form[0]);

        errorsDiv.hide().find('ul').empty();
        submitBtn.prop('disabled', true).text('Saving...');

        var options = {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        };

        fetch(url, options)
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, status: res.status, data: data }; }); })
            .then(function(result) {
                submitBtn.prop('disabled', false).text('Save');
                if (result.ok) {
                    modal.modal('hide');
                    location.reload();
                } else {
                    if (result.data && result.data.errors) {
                        var errs = result.data.errors;
                        for (var field in errs) {
                            if (errs.hasOwnProperty(field)) {
                                errs[field].forEach(function(msg) {
                                    errorsList.append('<li>' + msg + '</li>');
                                });
                            }
                        }
                        errorsDiv.show();
                    } else {
                        errorsList.append('<li>' + (result.data && result.data.message ? result.data.message : 'An error occurred.') + '</li>');
                        errorsDiv.show();
                    }
                }
            })
            .catch(function() {
                submitBtn.prop('disabled', false).text('Save');
                errorsList.append('<li>Network error. Please try again.</li>');
                errorsDiv.show();
            });
    });
})();
</script>
@endpush
