@extends('admin.layout')

@section('title', 'New Purchase')

@section('content')
<h3 class="page-title"> New Purchase <small>add stock from supplier</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.purchases.index') }}">Purchases</a><i class="fa fa-angle-right"></i></li>
        <li><span>New</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchase-form">
                    @csrf
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Optional --</option>
                            @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <hr>
                    <h5>Items (stock will be added to product)</h5>
                    <table class="table table-bordered" id="purchase-items">
                        <thead><tr><th>Product</th><th>Qty</th><th>Unit cost</th><th>Line total</th><th></th></tr></thead>
                        <tbody>
                            <tr class="purchase-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Select product</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-name="{{ $p->name }}">{{ $p->name }} (stock: {{ $p->stock_quantity }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][quantity]" class="form-control qty" min="1" value="1" required></td>
                                <td><input type="number" step="0.01" name="items[0][unit_cost]" class="form-control unit-cost" min="0" value="0" required></td>
                                <td class="line-total">0.00</td>
                                <td><button type="button" class="btn btn-xs red remove-row">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm default" id="add-row">+ Add row</button>
                    <hr>
                    <button type="submit" class="btn green">Save purchase & update stock</button>
                    <a href="{{ route('admin.purchases.index') }}" class="btn default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var tbody = $('#purchase-items tbody');
    var rowIndex = 1;
    function addRow() {
        var firstRow = tbody.find('tr').first();
        var newRow = firstRow.clone();
        newRow.find('select').attr('name', 'items[' + rowIndex + '][product_id]').val('');
        newRow.find('.qty').attr('name', 'items[' + rowIndex + '][quantity]').val(1);
        newRow.find('.unit-cost').attr('name', 'items[' + rowIndex + '][unit_cost]').val(0);
        newRow.find('.line-total').text('0.00');
        tbody.append(newRow);
        rowIndex++;
    }
    function updateLineTotal(row) {
        var qty = parseFloat($(row).find('.qty').val()) || 0;
        var cost = parseFloat($(row).find('.unit-cost').val()) || 0;
        $(row).find('.line-total').text((qty * cost).toFixed(2));
    }
    tbody.on('change keyup', '.qty, .unit-cost', function() {
        updateLineTotal($(this).closest('tr'));
    });
    $('#add-row').on('click', addRow);
    tbody.on('click', '.remove-row', function() {
        if (tbody.find('tr').length > 1) $(this).closest('tr').remove();
    });
})();
</script>
@endpush
