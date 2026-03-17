@extends('admin.layout')

@section('title', 'Products')

@section('content')
<h3 class="page-title"> Products
    <small>list & manage</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Products</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-basket font-dark"></i>
                    <span class="caption-subject bold uppercase">Products</span>
                </div>
                <div class="actions">
                    <button type="button" class="btn btn-sm green" id="btn-add-product"> Add New
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="portlet-body">
                @php
                    $currentSort = request('sort', 'id');
                    $currentDir = strtolower(request('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
                    $sortUrl = function($col, $defaultDir = 'asc') use ($currentSort, $currentDir) {
                        $params = request()->only('q');
                        $params['sort'] = $col;
                        $params['dir'] = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
                        return route('admin.products.index', $params);
                    };
                    $sortIcon = function($col) use ($currentSort, $currentDir) {
                        if ($currentSort !== $col) return ' <i class="fa fa-sort text-muted" style="font-size:11px;"></i>';
                        return $currentDir === 'asc' ? ' <i class="fa fa-sort-asc"></i>' : ' <i class="fa fa-sort-desc"></i>';
                    };
                @endphp
                <form method="GET" action="{{ route('admin.products.index') }}" id="product-search-form" class="form-inline margin-bottom-15" role="form">
                    <div class="form-group">
                        <input type="text" name="q" id="product-search-input" class="form-control input-medium" placeholder="Search by name, slug, brand, category..." value="{{ request('q') }}" style="min-width:260px;" autocomplete="off">
                    </div>
                    <input type="hidden" name="sort" value="{{ $currentSort }}">
                    <input type="hidden" name="dir" value="{{ $currentDir }}">
                    <button type="submit" class="btn blue"><i class="fa fa-search"></i> Search</button>
                    @if(request('q'))
                        <a href="{{ route('admin.products.index') }}" class="btn default">Clear</a>
                    @endif
                </form>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th><a href="{{ $sortUrl('name') }}" class="sortable-th"> Name{!! $sortIcon('name') !!}</a></th>
                                <th><a href="{{ $sortUrl('category') }}" class="sortable-th"> Category{!! $sortIcon('category') !!}</a></th>
                                <th><a href="{{ $sortUrl('brand') }}" class="sortable-th"> Brand{!! $sortIcon('brand') !!}</a></th>
                                <th><a href="{{ $sortUrl('wholesale_price') }}" class="sortable-th"> Wholesale (cost){!! $sortIcon('wholesale_price') !!}</a></th>
                                <th><a href="{{ $sortUrl('retail_price') }}" class="sortable-th"> Retail (selling){!! $sortIcon('retail_price') !!}</a></th>
                                <th><a href="{{ $sortUrl('profit') }}" class="sortable-th"> Profit{!! $sortIcon('profit') !!}</a></th>
                                <th><a href="{{ $sortUrl('stock_quantity') }}" class="sortable-th"> Stock{!! $sortIcon('stock_quantity') !!}</a></th>
                                <th> Status </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            @php
                                $profit = (float) $product->retail_price - (float) $product->wholesale_price;
                            @endphp
                            <tr>
                                <td> {{ $product->id }} </td>
                                <td> {{ $product->name }} </td>
                                <td> {{ $product->category->name ?? '-' }} </td>
                                <td> {{ $product->brand ?? '-' }} </td>
                                <td> {{ $currency }}{{ number_format($product->wholesale_price, 2) }} </td>
                                <td> {{ $currency }}{{ number_format($product->retail_price, 2) }} </td>
                                <td> {{ $currency }}{{ number_format($profit, 2) }} <span class="text-muted">({{ $product->retail_price > 0 ? number_format(($profit / (float) $product->retail_price) * 100, 0) : 0 }}%)</span> </td>
                                <td>
                                    @if($product->isLowStock())
                                        <span class="label label-sm label-danger" title="Below minimum ({{ $product->minimum_stock_limit }})">{{ $product->stock_quantity }}</span>
                                    @else
                                        {{ $product->stock_quantity }}
                                    @endif
                                </td>
                                <td>
                                    <label class="product-status-toggle mt-checkbox mt-checkbox-outline" title="{{ $product->is_active ? 'Active (click to deactivate)' : 'Inactive (click to activate)' }}">
                                        <input type="checkbox" class="status-checkbox" data-product-id="{{ $product->id }}" data-url="{{ route('admin.products.status', $product) }}" {{ $product->is_active ? 'checked' : '' }}>
                                        <span class="status-label">{{ $product->is_active ? ' Active ' : ' Inactive ' }}</span>
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs blue btn-edit-product" data-id="{{ $product->id }}"> Edit </button>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs red"> Delete </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No products yet. <button type="button" class="btn btn-link p-0" id="btn-add-product-empty">Add one</button>.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid #eef1f5; padding: 15px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="productModalLabel" style="margin: 0; font-weight: 600;">
                    <i class="icon-basket"></i> Add New Product
                </h4>
            </div>
            <form id="product-form" name="product-form">
                <div class="modal-body" style="padding: 20px 20px 10px; max-height: 70vh; overflow-y: auto;">
                    <div id="product-modal-errors" class="alert alert-danger" style="display:none;">
                        <ul class="mb-0 list-unstyled" id="product-modal-errors-list"></ul>
                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="product-edit-id" name="product_edit_id" value="">

                    <div class="form-section" style="margin-bottom: 24px;">
                        <h5 style="margin: 0 0 14px 0; padding-bottom: 8px; border-bottom: 2px solid #3598dc; color: #3598dc; font-weight: 600;">Basic information</h5>
                        <div class="form-group" id="product-existing-images-wrap" style="display: none;">
                            <label>Existing images</label>
                            <div id="product-existing-images" class="row" style="margin-bottom: 12px;"></div>
                        </div>
                        <div class="form-group">
                            <label>Product images</label>
                            <div id="product-images-dropzone" class="dropzone product-image-dropzone" style="min-height: 140px; border: 2px dashed #3598dc; border-radius: 6px; background: #fafafa;">
                                <div class="dz-message" style="margin: 2em 0; color: #3598dc;">
                                    <i class="fa fa-cloud-upload" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                                    Drop images here or click to upload (max 5MB each)
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-category">Category <span class="required" style="color:#e73d4a;">*</span></label>
                                    <select id="product-category" name="category_id" class="form-control">
                                        <option value="">Select category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-name">Product name <span class="required" style="color:#e73d4a;">*</span></label>
                                    <input type="text" id="product-name" name="name" class="form-control" placeholder="Enter product name" maxlength="255">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-slug">Slug</label>
                                    <input type="text" id="product-slug" name="slug" class="form-control" placeholder="Leave blank to auto-generate" maxlength="255">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-brand">Brand</label>
                                    <select id="product-brand" name="brand_id" class="form-control">
                                        <option value="">Select brand</option>
                                        @foreach($brands as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-wholesale">Wholesale (buying) price <span class="required" style="color:#e73d4a;">*</span></label>
                                    <input type="number" id="product-wholesale" name="wholesale_price" class="form-control" step="0.01" min="0" value="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-retail">Retail (selling) price <span class="required" style="color:#e73d4a;">*</span></label>
                                    <input type="number" id="product-retail" name="retail_price" class="form-control" step="0.01" min="0" value="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-condition">Condition</label>
                                    <input type="text" id="product-condition" name="condition" class="form-control" value="New" placeholder="e.g. New, Refurbished" maxlength="255">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-stock">Stock quantity</label>
                                    <input type="number" id="product-stock" name="stock_quantity" class="form-control" min="0" value="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-min-stock">Min. stock (alert below)</label>
                                    <input type="number" id="product-min-stock" name="minimum_stock_limit" class="form-control" min="0" value="0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-top: 28px;">
                                    <label class="mt-checkbox mt-checkbox-outline" style="font-weight: normal;">
                                        <input type="checkbox" name="is_active" value="1" id="product-active" checked> Active (visible in store)
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 20px;">
                        <h5 style="margin: 0 0 14px 0; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; color: #555; font-weight: 600;">Sale</h5>
                        <div class="form-group">
                            <label class="mt-checkbox mt-checkbox-outline" style="font-weight: normal;">
                                <input type="checkbox" name="is_on_sale" value="1" id="product-on-sale"> Mark as On Sale
                                <span></span>
                            </label>
                        </div>
                        <div class="row" id="product-sale-fields">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product-sale-discount">Discount (%)</label>
                                    <input type="number" id="product-sale-discount" name="sale_discount_percent" class="form-control" min="0" max="100" step="0.01" value="0" placeholder="e.g. 10">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discounted price</label>
                                    <p class="form-control-static" id="product-discounted-price">—</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section" style="margin-bottom: 20px;">
                        <h5 style="margin: 0 0 14px 0; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; color: #555; font-weight: 600;">Variant options (Color, Storage, Size, Condition)</h5>
                        <p class="text-muted" style="font-size: 12px; margin-bottom: 12px;">Add option types and their values. These appear on the product page so customers can pick e.g. Color, Storage, Size. Each option can have multiple values. Variants must match these values (color, storage, size, condition on each variant).</p>
                        <div id="product-variant-options-container">
                            <!-- option blocks appended by JS -->
                        </div>
                        <button type="button" class="btn btn-sm default mt-2" id="btn-add-variant-option">
                            <i class="fa fa-plus"></i> Add option (Color, Storage, Size, Condition)
                        </button>
                    </div>

                    <div class="form-section" style="margin-bottom: 20px;">
                        <h5 style="margin: 0 0 14px 0; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; color: #555; font-weight: 600;">Description & policies</h5>
                        <div class="form-group">
                            <label for="product-description-modal">Description</label>
                            <textarea id="product-description-modal" name="description" class="form-control" rows="4" placeholder="Product description"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-payment">Payment info</label>
                                    <textarea id="product-payment" name="payment_info" class="form-control" rows="2" placeholder="We accept credit/debit cards and PayPal. Full payment at checkout."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-shipping">Shipping info</label>
                                    <textarea id="product-shipping" name="shipping_info" class="form-control" rows="2" placeholder="Standard delivery 3–5 business days. Free shipping on orders over $50."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-returns">Returns info</label>
                                    <textarea id="product-returns" name="returns_info" class="form-control" rows="2" placeholder="30-day return policy. Item must be unused and in original packaging."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-warranty">Warranty info</label>
                                    <textarea id="product-warranty" name="warranty_info" class="form-control" rows="2" placeholder="1-year manufacturer warranty. Proof of purchase required."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product-other">Other policies</label>
                            <textarea id="product-other" name="other_policies" class="form-control" rows="2" placeholder="All sales subject to our terms of service. Contact us for bulk orders."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef1f5; padding: 12px 20px;">
                    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn green" id="product-form-submit">
                        <i class="fa fa-check"></i> Save product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('admin/theme/assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<style>
#productModal .form-group { margin-bottom: 16px; }
#productModal .product-image-dropzone.dz-started .dz-message { display: block; margin: 0.5em 0; font-size: 12px; }
#productModal .form-group:last-child { margin-bottom: 0; }
#productModal label { font-weight: 500; color: #333; margin-bottom: 6px; display: block; }
#productModal .form-control { border-radius: 3px; }
#productModal .modal-body::-webkit-scrollbar { width: 6px; }
#productModal .modal-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
#productModal .modal-body::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
#productModal .form-section h5 { font-size: 14px; }
#product-form .error { color: #e73d4a; font-size: 12px; margin-top: 4px; }
#product-form input.error, #product-form select.error { border-color: #e73d4a; }
.sortable-th { color: inherit; text-decoration: none; font-weight: inherit; }
.sortable-th:hover { color: #3598dc; text-decoration: none; }
.product-status-toggle { cursor: pointer; margin: 0; font-weight: normal; display: inline-block; }
.product-status-toggle .status-label { margin-left: 4px; }
.product-status-toggle input.status-checkbox { margin-right: 2px; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('product-search-input');
    var searchForm = document.getElementById('product-search-form');
    if (searchInput && searchForm) {
        var debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                searchForm.submit();
            }, 350);
        });
    }
})();
</script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="{{ asset('admin/theme/assets/global/plugins/dropzone/dropzone.min.js') }}"></script>
<script>
if (typeof Dropzone !== 'undefined') { Dropzone.autoDiscover = false; }
</script>
<script src="{{ asset('admin/theme/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin/theme/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"></script>
<script>
(function() {
    var modal = $('#productModal');
    var form = $('#product-form');
    var errorsDiv = $('#product-modal-errors');
    var errorsList = $('#product-modal-errors-list');
    var storeUrl = '{{ route("admin.products.store") }}';
    var baseProductsUrl = '{{ url("admin-panel/products") }}';
    var validator;
    var productDropzone;
    var productDeleteImageIds = [];
    var MODAL_DESC_ID = 'product-description-modal';

    function initModalEditor() {
        if (typeof CKEDITOR === 'undefined') return;
        if (CKEDITOR.instances[MODAL_DESC_ID]) return;
        CKEDITOR.replace(MODAL_DESC_ID, {
            height: 180,
            removeButtons: 'Source,Image,Flash,Table,HorizontalRule,SpecialChar,Maximize,About'
        });
    }

    function destroyModalEditor() {
        if (typeof CKEDITOR === 'undefined') return;
        if (CKEDITOR.instances[MODAL_DESC_ID]) {
            CKEDITOR.instances[MODAL_DESC_ID].destroy(true);
        }
    }

    function initDropzone() {
        if (productDropzone) return;
        if (typeof Dropzone === 'undefined') return;
        try {
            productDropzone = new Dropzone('#product-images-dropzone', {
                url: '#',
                autoProcessQueue: false,
                addRemoveLinks: true,
                acceptedFiles: 'image/*',
                maxFilesize: 5,
                dictDefaultMessage: '',
                dictRemoveFile: 'Remove',
                dictFileTooBig: 'File is too big. Max 5MB.',
                init: function() {
                    this.on('addedfile', function() {
                        if (this.files.length > 10) this.removeFile(this.files[0]);
                    });
                }
            });
            window.productDropzone = productDropzone;
        } catch (e) { console.warn('Dropzone init:', e); }
    }

    var defaultPolicyText = {
        payment: 'We accept credit/debit cards and PayPal. Full payment at checkout.',
        shipping: 'Standard delivery 3–5 business days. Free shipping on orders over $50.',
        returns: '30-day return policy. Item must be unused and in original packaging.',
        warranty: '1-year manufacturer warranty. Proof of purchase required.',
        other: 'All sales subject to our terms of service. Contact us for bulk orders.'
    };

    function updateDiscountedPrice() {
        var onSale = form.find('#product-on-sale').is(':checked');
        var retail = parseFloat(form.find('#product-retail').val()) || 0;
        var discount = parseFloat(form.find('#product-sale-discount').val()) || 0;
        var el = $('#product-discounted-price');
        if (!onSale || discount <= 0) { el.text('—'); return; }
        var discounted = Math.round(retail * (1 - discount / 100) * 100) / 100;
        el.text((window.adminCurrencySymbol || '£') + discounted.toFixed(2));
    }
    form.find('#product-retail, #product-sale-discount').on('input change', updateDiscountedPrice);
    form.find('#product-on-sale').on('change', updateDiscountedPrice);

    var OPTION_KEYS = [
        { key: 'color', label: 'Color' },
        { key: 'storage', label: 'Storage' },
        { key: 'size', label: 'Size' },
        { key: 'condition', label: 'Condition' }
    ];
    var optionBlockIndex = 0;
    var optionContainer = $('#product-variant-options-container');

    function addOptionBlock(data) {
        data = data || { key: 'color', label: 'Color', values: [''] };
        var idx = optionBlockIndex++;
        var block = $('<div class="variant-option-block panel panel-default" style="margin-bottom:12px; padding:10px;" data-index="' + idx + '"></div>');
        var keySelect = $('<select class="form-control input-sm option-key" name="option_definitions[' + idx + '][key]" style="display:inline-block;width:120px;"></select>');
        OPTION_KEYS.forEach(function(o) {
            keySelect.append($('<option value="' + o.key + '">' + o.label + '</option>'));
        });
        keySelect.val(data.key || 'color');
        var labelInput = $('<input type="text" class="form-control input-sm option-label" name="option_definitions[' + idx + '][label]" placeholder="Label" style="display:inline-block;width:100px;margin-left:6px;">');
        labelInput.val(data.label || '');
        var valuesWrap = $('<div class="option-values-wrap mt-2"></div>');
        var values = (data.values && data.values.length) ? data.values : [''];
        values.forEach(function(v) {
            var row = $('<div class="option-value-row" style="margin-bottom:6px;"></div>');
            var valStr = (v != null && typeof v === 'object' && v.value !== undefined) ? v.value : (v || '');
            var input = $('<input type="text" class="form-control input-sm" name="option_definitions[' + idx + '][values][]" placeholder="Value" style="display:inline-block;width:150px;">');
            input.val(valStr);
            row.append(input);
            row.append($('<button type="button" class="btn btn-xs default remove-value" style="margin-left:4px;">&times;</button>'));
            row.find('.remove-value').on('click', function() {
                if (valuesWrap.find('.option-value-row').length > 1) row.remove();
            });
            valuesWrap.append(row);
        });
        var addValBtn = $('<button type="button" class="btn btn-xs green add-value mt-1">+ Add value</button>');
        addValBtn.on('click', function() {
            var row = $('<div class="option-value-row" style="margin-bottom:6px;"></div>');
            row.append($('<input type="text" class="form-control input-sm" name="option_definitions[' + idx + '][values][]" placeholder="Value" style="display:inline-block;width:150px;">'));
            row.append($('<button type="button" class="btn btn-xs default remove-value" style="margin-left:4px;">&times;</button>'));
            row.find('.remove-value').on('click', function() {
                if (valuesWrap.find('.option-value-row').length > 1) row.remove();
            });
            valuesWrap.append(row);
        });
        var removeOptBtn = $('<button type="button" class="btn btn-xs red btn-remove-option" style="margin-left:8px;">Remove option</button>');
        removeOptBtn.on('click', function() { block.remove(); });
        keySelect.on('change', function() {
            var v = keySelect.val();
            var sel = OPTION_KEYS.find(function(o) { return o.key === v; });
            if (sel) labelInput.val(sel.label);
        });
        block.append($('<div class="row"><div class="col-md-12"></div></div>').find('.col-md-12').append(keySelect).append(labelInput).append(removeOptBtn).end());
        block.append(valuesWrap);
        block.append(addValBtn);
        optionContainer.append(block);
    }

    $('#btn-add-variant-option').on('click', function() { addOptionBlock(); });

    function resetVariantOptions() {
        optionContainer.empty();
        optionBlockIndex = 0;
    }

    function renderVariantOptions(optionDefinitions) {
        resetVariantOptions();
        if (optionDefinitions && optionDefinitions.length) {
            optionDefinitions.forEach(function(def) {
                addOptionBlock({
                    key: def.option_key || def.key,
                    label: def.option_label || def.label,
                    values: (def.values && def.values.length) ? def.values.map(function(v) { return v.value || v; }) : (def.value_list || [])
                });
            });
        }
    }

    function resetForm() {
        form[0].reset();
        form.find('#product-edit-id').val('');
        form.find('#product-wholesale').val('0');
        form.find('#product-retail').val('0');
        form.find('#product-stock').val('0');
        form.find('#product-min-stock').val('0');
        form.find('#product-on-sale').prop('checked', false);
        form.find('#product-sale-discount').val('0');
        updateDiscountedPrice();
        form.find('#product-active').prop('checked', true);
        form.find('#product-payment').val(defaultPolicyText.payment);
        form.find('#product-shipping').val(defaultPolicyText.shipping);
        form.find('#product-returns').val(defaultPolicyText.returns);
        form.find('#product-warranty').val(defaultPolicyText.warranty);
        form.find('#product-other').val(defaultPolicyText.other);
        errorsDiv.hide().find('ul').empty();
        productDeleteImageIds = [];
        $('#product-existing-images-wrap').hide().find('#product-existing-images').empty();
        resetVariantOptions();
        if (validator) validator.resetForm();
        if (window.productDropzone) try { window.productDropzone.removeAllFiles(true); } catch (e) {}
    }

    function renderExistingImages(images) {
        var container = $('#product-existing-images');
        container.empty();
        if (!images || !images.length) return;
        images.forEach(function(img) {
            var src = img.full_url || img.url;
            var col = $('<div class="col-md-2 col-xs-4" style="margin-bottom:10px;"></div>');
            var wrap = $('<div style="position:relative;"></div>');
            var thumb = $('<img>').attr('src', src).css({ width: '100%', height: '70px', objectFit: 'cover', borderRadius: '4px', border: '1px solid #ddd' }).on('error', function() { $(this).attr('src', 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="60"/>'); });
            var rem = $('<button type="button" class="btn btn-xs red" style="position:absolute;top:2px;right:2px;">&times;</button>');
            rem.on('click', function() {
                productDeleteImageIds.push(img.id);
                col.fadeOut(200, function() { col.remove(); });
            });
            wrap.append(thumb).append(rem);
            col.append(wrap);
            container.append(col);
        });
        $('#product-existing-images-wrap').show();
    }

    modal.on('shown.bs.modal', function() {
        initModalEditor();
    });
    modal.on('hidden.bs.modal', function() {
        destroyModalEditor();
    });

    $(document).on('click', '#btn-add-product, #btn-add-product-empty', function(e) {
        e.preventDefault();
        resetForm();
        $('#productModalLabel').html('<i class="icon-basket"></i> Add New Product');
        modal.modal('show');
        setTimeout(function() {
            initDropzone();
            form.find('#product-category').focus();
        }, 350);
    });

    $(document).on('click', '.btn-edit-product', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (!id) return;
        resetForm();
        initDropzone();
        $('#productModalLabel').html('<i class="icon-basket"></i> Edit Product');
        var editUrl = baseProductsUrl + '/' + id;
        fetch(editUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var p = data.product;
                if (!p) return;
                form.find('#product-edit-id').val(p.id);
                form.find('#product-category').val(p.category_id);
                form.find('#product-name').val(p.name);
                form.find('#product-slug').val(p.slug || '');
                form.find('#product-wholesale').val(p.wholesale_price ?? p.base_price ?? '0');
                form.find('#product-retail').val(p.retail_price ?? p.base_price ?? '0');
                form.find('#product-stock').val(p.stock_quantity ?? '0');
                form.find('#product-min-stock').val(p.minimum_stock_limit ?? '0');
                form.find('#product-on-sale').prop('checked', !!p.is_on_sale);
                form.find('#product-sale-discount').val(p.sale_discount_percent ?? '0');
                updateDiscountedPrice();
                form.find('#product-brand').val(p.brand_id || '');
                form.find('#product-condition').val(p.condition || 'New');
                form.find('#product-active').prop('checked', !!p.is_active);
                renderVariantOptions(p.option_definitions || []);
                form.find('#product-description-modal').val(p.description || '');
                form.find('#product-payment').val(p.payment_info || defaultPolicyText.payment);
                form.find('#product-shipping').val(p.shipping_info || defaultPolicyText.shipping);
                form.find('#product-returns').val(p.returns_info || defaultPolicyText.returns);
                form.find('#product-warranty').val(p.warranty_info || defaultPolicyText.warranty);
                form.find('#product-other').val(p.other_policies || defaultPolicyText.other);
                renderExistingImages(p.images || []);
                modal.modal('show');
                setTimeout(function() { form.find('#product-category').focus(); }, 300);
            })
            .catch(function() {
                alert('Could not load product. Try again.');
            });
    });

    validator = form.validate({
        rules: {
            category_id: { required: true },
            name: { required: true, maxlength: 255 },
            slug: { maxlength: 255 },
            wholesale_price: { required: true, number: true, min: 0 },
            retail_price: { required: true, number: true, min: 0 },
            brand: { maxlength: 255 },
            condition: { maxlength: 255 }
        },
        messages: {
            category_id: { required: 'Please select a category.' },
            name: { required: 'Please enter the product name.' },
            wholesale_price: {
                required: 'Please enter the wholesale (buying) price.',
                number: 'Please enter a valid number.',
                min: 'Price cannot be negative.'
            },
            retail_price: {
                required: 'Please enter the retail (selling) price.',
                number: 'Please enter a valid number.',
                min: 'Price cannot be negative.'
            }
        },
        errorClass: 'error',
        validClass: 'valid',
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('.form-group'));
        },
        highlight: function(element) {
            $(element).addClass('error').closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).removeClass('error').closest('.form-group').removeClass('has-error');
        }
    });

    form.on('submit', function(e) {
        e.preventDefault();
        if (!form.valid()) return;
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[MODAL_DESC_ID]) {
            CKEDITOR.instances[MODAL_DESC_ID].updateElement();
        }

        var submitBtn = $('#product-form-submit');
        var formData = new FormData(form[0]);
        formData.append('is_active', form.find('#product-active').is(':checked') ? '1' : '0');
        var editId = form.find('#product-edit-id').val();
        if (editId) {
            formData.append('_method', 'PUT');
            productDeleteImageIds.forEach(function(id) {
                formData.append('delete_image_ids[]', id);
            });
        }
        if (window.productDropzone && window.productDropzone.getAcceptedFiles().length) {
            window.productDropzone.getAcceptedFiles().forEach(function(f) {
                formData.append('images[]', f, f.name);
            });
        }

        var submitUrl = editId ? (baseProductsUrl + '/' + editId) : storeUrl;
        errorsDiv.hide().find('ul').empty();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, status: res.status, data: data }; }); })
        .then(function(result) {
            submitBtn.prop('disabled', false).html('<i class="fa fa-check"></i> Save product');
            if (result.ok) {
                modal.modal('hide');
                location.reload();
            } else {
                if (result.data && result.data.errors) {
                    for (var field in result.data.errors) {
                        if (result.data.errors.hasOwnProperty(field)) {
                            result.data.errors[field].forEach(function(msg) {
                                errorsList.append('<li>' + msg + '</li>');
                            });
                        }
                    }
                } else {
                    errorsList.append('<li>' + (result.data && result.data.message ? result.data.message : 'An error occurred.') + '</li>');
                }
                errorsDiv.show();
            }
        })
        .catch(function() {
            submitBtn.prop('disabled', false).html('<i class="fa fa-check"></i> Save product');
            errorsList.append('<li>Network error. Please try again.</li>');
            errorsDiv.show();
        });
    });

    $(document).on('change', '.status-checkbox', function() {
        var cb = $(this);
        var url = cb.data('url');
        var isActive = cb.is(':checked');
        var label = cb.closest('.product-status-toggle').find('.status-label');
        var originalChecked = cb.prop('checked');
        cb.prop('disabled', true);
        $.ajax({
            url: url,
            method: 'PATCH',
            data: { is_active: isActive ? 1 : 0, _token: '{{ csrf_token() }}' },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .done(function(data) {
            label.text(data.is_active ? ' Active ' : ' Inactive ');
        })
        .fail(function() {
            cb.prop('checked', !originalChecked);
            label.text(originalChecked ? ' Active ' : ' Inactive ');
        })
        .always(function() {
            cb.prop('disabled', false);
        });
    });
})();
</script>
@endpush
