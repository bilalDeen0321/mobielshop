@extends('admin.layout')

@section('title', 'Add Product')

@section('content')
<h3 class="page-title"> Add Product </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.products.index') }}">Products</a><i class="fa fa-angle-right"></i></li>
        <li><span>Add Product</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption"><i class="icon-basket"></i> New Product</div>
            </div>
            <div class="portlet-body form">
                <form action="{{ route('admin.products.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product images</label>
                            <div class="col-md-8">
                                <div id="create-product-dropzone" class="product-create-dropzone" style="border: 2px dashed #3598dc; border-radius: 6px; background: #fafafa; padding: 20px; text-align: center; cursor: pointer; min-height: 120px;">
                                    <input type="file" name="images[]" id="create-product-images" multiple accept="image/*" style="display: none;">
                                    <div class="dz-message-text" style="color: #3598dc;">
                                        <i class="fa fa-cloud-upload" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                                        Click or drop images here (max 5MB each, 10 files)
                                    </div>
                                    <div id="create-product-previews" class="row" style="margin-top: 12px; min-height: 0;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category <span class="required">*</span></label>
                            <div class="col-md-8">
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Slug</label>
                            <div class="col-md-8">
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate from name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Base Price <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="base_price" class="form-control" step="0.01" min="0" value="{{ old('base_price', '0') }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Brand</label>
                            <div class="col-md-8">
                                <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Condition</label>
                            <div class="col-md-8">
                                <input type="text" name="condition" class="form-control" value="{{ old('condition', 'New') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Active</label>
                            <div class="col-md-8">
                                <input type="checkbox" name="is_active" value="1" class="make-switch" data-size="small" {{ old('is_active', true) ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Payment Info</label>
                            <div class="col-md-8">
                                <textarea name="payment_info" class="form-control" rows="2" placeholder="We accept credit/debit cards and PayPal. Full payment at checkout.">{{ old('payment_info', 'We accept credit/debit cards and PayPal. Full payment at checkout.') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Shipping Info</label>
                            <div class="col-md-8">
                                <textarea name="shipping_info" class="form-control" rows="2" placeholder="Standard delivery 3–5 business days. Free shipping on orders over $50.">{{ old('shipping_info', 'Standard delivery 3–5 business days. Free shipping on orders over $50.') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Returns Info</label>
                            <div class="col-md-8">
                                <textarea name="returns_info" class="form-control" rows="2" placeholder="30-day return policy. Item must be unused and in original packaging.">{{ old('returns_info', '30-day return policy. Item must be unused and in original packaging.') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Warranty Info</label>
                            <div class="col-md-8">
                                <textarea name="warranty_info" class="form-control" rows="2" placeholder="1-year manufacturer warranty. Proof of purchase required.">{{ old('warranty_info', '1-year manufacturer warranty. Proof of purchase required.') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Other Policies</label>
                            <div class="col-md-8">
                                <textarea name="other_policies" class="form-control" rows="2" placeholder="All sales subject to our terms of service. Contact us for bulk orders.">{{ old('other_policies', 'All sales subject to our terms of service. Contact us for bulk orders.') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <button type="submit" class="btn green">Save</button>
                                <a href="{{ route('admin.products.index') }}" class="btn default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var dropzone = document.getElementById('create-product-dropzone');
    var input = document.getElementById('create-product-images');
    var previews = document.getElementById('create-product-previews');
    if (dropzone && input) {
        dropzone.addEventListener('click', function() { input.click(); });
        dropzone.addEventListener('dragover', function(e) { e.preventDefault(); dropzone.style.background = '#e8f4fc'; });
        dropzone.addEventListener('dragleave', function() { dropzone.style.background = '#fafafa'; });
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.style.background = '#fafafa';
            if (e.dataTransfer.files.length) input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        });
        input.addEventListener('change', function() {
            previews.innerHTML = '';
            var files = Array.from(input.files || []);
            files.slice(0, 10).forEach(function(file, i) {
                if (!file.type.match('image.*')) return;
                var col = document.createElement('div');
                col.className = 'col-md-2 col-xs-4';
                col.style.marginBottom = '10px';
                var wrap = document.createElement('div');
                wrap.style.position = 'relative';
                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.width = '100%'; img.style.height = '70px'; img.style.objectFit = 'cover'; img.style.borderRadius = '4px';
                var rem = document.createElement('button');
                rem.type = 'button';
                rem.className = 'btn btn-xs red';
                rem.style.position = 'absolute'; rem.style.top = '2px'; rem.style.right = '2px';
                rem.innerHTML = '&times;';
                rem.onclick = function() {
                    var dt = new DataTransfer();
                    Array.from(input.files).forEach(function(f, j) { if (j !== i) dt.items.add(f); });
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change'));
                };
                wrap.appendChild(img);
                wrap.appendChild(rem);
                col.appendChild(wrap);
                previews.appendChild(col);
            });
        });
    }
})();
</script>
@endpush
