@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<h3 class="page-title"> Edit Product </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.products.index') }}">Products</a><i class="fa fa-angle-right"></i></li>
        <li><span>Edit</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption"><i class="icon-basket"></i> Edit Product</div>
            </div>
            <div class="portlet-body form">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category <span class="required">*</span></label>
                            <div class="col-md-8">
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Slug</label>
                            <div class="col-md-8">
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Wholesale (buying) price <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="wholesale_price" class="form-control" step="0.01" min="0" value="{{ old('wholesale_price', $product->wholesale_price) }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Retail (selling) price <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="retail_price" class="form-control" step="0.01" min="0" value="{{ old('retail_price', $product->retail_price) }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Stock quantity</label>
                            <div class="col-md-8">
                                <input type="number" name="stock_quantity" class="form-control" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Min. stock (alert below)</label>
                            <div class="col-md-8">
                                <input type="number" name="minimum_stock_limit" class="form-control" min="0" value="{{ old('minimum_stock_limit', $product->minimum_stock_limit) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sale</label>
                            <div class="col-md-8">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}> Mark as On Sale
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sale discount (%)</label>
                            <div class="col-md-8">
                                <input type="number" name="sale_discount_percent" class="form-control" min="0" max="100" step="0.01" value="{{ old('sale_discount_percent', $product->sale_discount_percent ?? 0) }}" placeholder="e.g. 10">
                                <span class="help-block">Discounted price = @if($product->is_on_sale && $product->sale_price) £{{ number_format($product->sale_price, 2) }} @else — @endif (when on sale).</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Brand</label>
                            <div class="col-md-8">
                                <select name="brand_id" class="form-control">
                                    <option value="">Select brand</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Condition</label>
                            <div class="col-md-8">
                                <input type="text" name="condition" class="form-control" value="{{ old('condition', $product->condition) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Active</label>
                            <div class="col-md-8">
                                <input type="checkbox" name="is_active" value="1" class="make-switch" data-size="small" {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Payment Info</label>
                            <div class="col-md-8">
                                <textarea name="payment_info" class="form-control" rows="2" placeholder="We accept credit/debit cards and PayPal. Full payment at checkout.">{{ old('payment_info', $product->payment_info) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Shipping Info</label>
                            <div class="col-md-8">
                                <textarea name="shipping_info" class="form-control" rows="2" placeholder="Standard delivery 3–5 business days. Free shipping on orders over $50.">{{ old('shipping_info', $product->shipping_info) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Returns Info</label>
                            <div class="col-md-8">
                                <textarea name="returns_info" class="form-control" rows="2" placeholder="30-day return policy. Item must be unused and in original packaging.">{{ old('returns_info', $product->returns_info) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Warranty Info</label>
                            <div class="col-md-8">
                                <textarea name="warranty_info" class="form-control" rows="2" placeholder="1-year manufacturer warranty. Proof of purchase required.">{{ old('warranty_info', $product->warranty_info) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Other Policies</label>
                            <div class="col-md-8">
                                <textarea name="other_policies" class="form-control" rows="2" placeholder="All sales subject to our terms of service. Contact us for bulk orders.">{{ old('other_policies', $product->other_policies) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <button type="submit" class="btn green">Update</button>
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
