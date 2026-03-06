@extends('admin.layout')

@section('title', 'Point of Sale')

@section('content')
<h3 class="page-title"> Point of Sale
    <small>create sale & manage transactions</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>POS</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-magnifier font-dark"></i>
                    <span class="caption-subject bold uppercase">Search products</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <input type="text" id="pos-search" class="form-control input-lg" placeholder="Type product name, brand..." autocomplete="off">
                </div>
                <div id="pos-search-results" class="list-group" style="max-height: 320px; overflow-y: auto;"></div>
                <p id="pos-search-hint" class="text-muted small">Start typing to search. Click a product to add to cart.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-basket font-dark"></i>
                    <span class="caption-subject bold uppercase">Cart</span>
                </div>
                <div class="actions">
                    <button type="button" class="btn btn-sm red" id="pos-clear-cart">Clear cart</button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="width: 90px;">Qty</th>
                                <th style="width: 90px;">Price</th>
                                <th style="width: 90px;">Total</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="pos-cart-body">
                            <tr id="pos-cart-empty"><td colspan="5" class="text-center text-muted">Cart is empty</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tax rate (%)</label>
                            <input type="number" id="pos-tax-rate" class="form-control" min="0" max="100" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Discount (£)</label>
                            <input type="number" id="pos-discount" class="form-control" min="0" step="0.01" value="0">
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <strong>Subtotal:</strong> £<span id="pos-subtotal">0.00</span><br>
                    <strong>Tax:</strong> £<span id="pos-tax">0.00</span><br>
                    <strong>Discount:</strong> -£<span id="pos-discount-display">0.00</span><br>
                    <strong class="font-lg">Total:</strong> £<span id="pos-total">0.00</span>
                </div>
                <div class="form-group">
                    <label>Payment method</label>
                    <select id="pos-payment-method" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="transfer">Bank transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Customer name</label>
                    <input type="text" id="pos-customer-name" class="form-control" placeholder="Optional">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" id="pos-customer-phone" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="pos-customer-email" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea id="pos-notes" class="form-control" rows="2" placeholder="Optional"></textarea>
                </div>
                <button type="button" class="btn green btn-block btn-lg" id="pos-complete-sale">
                    <i class="fa fa-check"></i> Complete sale
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt modal -->
<div class="modal fade" id="pos-receipt-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sale completed</h4>
            </div>
            <div class="modal-body" id="pos-receipt-body">
                <p><strong>Sale #:</strong> <span id="pos-receipt-number"></span></p>
                <p><strong>Total:</strong> £<span id="pos-receipt-total"></span></p>
                <hr>
                <p class="small text-muted">Stock has been updated. You can print this as a receipt.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <button type="button" class="btn blue" id="pos-print-receipt"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var cart = [];
    var searchUrl = '{{ route("admin.pos.search") }}';
    var completeUrl = '{{ route("admin.pos.complete") }}';
    var token = '{{ csrf_token() }}';

    function formatMoney(n) { return Number(n).toFixed(2); }

    function renderCart() {
        var tbody = $('#pos-cart-body');
        tbody.find('tr:not(#pos-cart-empty)').remove();
        if (cart.length === 0) {
            $('#pos-cart-empty').show();
            $('#pos-subtotal').text('0.00');
            $('#pos-tax').text('0.00');
            $('#pos-discount-display').text('0.00');
            $('#pos-total').text('0.00');
            return;
        }
        $('#pos-cart-empty').hide();
        var subtotal = 0;
        cart.forEach(function(item, index) {
            var lineTotal = item.unit_price * item.quantity;
            subtotal += lineTotal;
            var tr = $('<tr data-index="' + index + '"></tr>');
            tr.append('<td>' + item.name + '</td>');
            var qtyInput = $('<input type="number" class="form-control input-sm pos-cart-qty" min="1" max="' + item.stock_quantity + '" value="' + item.quantity + '">');
            tr.append($('<td></td>').append(qtyInput));
            tr.append('<td>£' + formatMoney(item.unit_price) + '</td>');
            tr.append('<td class="pos-line-total">£' + formatMoney(lineTotal) + '</td>');
            tr.append('<td><button type="button" class="btn btn-xs red pos-remove-item"><i class="fa fa-times"></i></button></td>');
            tbody.append(tr);
        });
        updateTotals();
    }

    function updateTotals() {
        var subtotal = 0;
        cart.forEach(function(item) { subtotal += item.unit_price * item.quantity; });
        var taxRate = parseFloat($('#pos-tax-rate').val()) || 0;
        var discount = parseFloat($('#pos-discount').val()) || 0;
        var tax = Math.round(subtotal * (taxRate / 100) * 100) / 100;
        var total = Math.round((subtotal + tax - discount) * 100) / 100;
        $('#pos-subtotal').text(formatMoney(subtotal));
        $('#pos-tax').text(formatMoney(tax));
        $('#pos-discount-display').text(formatMoney(discount));
        $('#pos-total').text(formatMoney(total));
    }

    function addToCart(product, quantity) {
        quantity = quantity || 1;
        var existing = cart.find(function(item) { return item.product_id === product.id; });
        if (existing) {
            var newQty = existing.quantity + quantity;
            if (newQty > product.stock_quantity) newQty = product.stock_quantity;
            existing.quantity = newQty;
        } else {
            if (quantity > product.stock_quantity) quantity = product.stock_quantity;
            if (quantity < 1) return;
            cart.push({
                product_id: product.id,
                name: product.name,
                quantity: quantity,
                unit_price: product.retail_price,
                stock_quantity: product.stock_quantity
            });
        }
        renderCart();
    }

    var searchTimer;
    $('#pos-search').on('input', function() {
        var q = $(this).val().trim();
        clearTimeout(searchTimer);
        var container = $('#pos-search-results');
        var hint = $('#pos-search-hint');
        if (q.length < 2) {
            container.empty();
            hint.show();
            return;
        }
        searchTimer = setTimeout(function() {
            hint.hide();
            container.html('<li class="list-group-item text-muted">Searching...</li>');
            $.get(searchUrl, { q: q })
                .done(function(data) {
                    container.empty();
                    if (!data || data.length === 0) {
                        container.append('<li class="list-group-item text-muted">No products found</li>');
                        return;
                    }
                    data.forEach(function(p) {
                        var inCart = cart.find(function(item) { return item.product_id === p.id; });
                        var available = p.stock_quantity - (inCart ? inCart.quantity : 0);
                        if (available <= 0) return;
                        var li = $('<a href="javascript:;" class="list-group-item pos-add-product"></a>');
                        li.data('product', p);
                        li.html('<strong>' + p.name + '</strong> ' + (p.brand ? ' (' + p.brand + ')' : '') + ' &mdash; £' + formatMoney(p.retail_price) + ' &nbsp; <span class="badge">Stock: ' + p.stock_quantity + '</span>');
                        container.append(li);
                    });
                })
                .fail(function() { container.html('<li class="list-group-item text-danger">Search failed</li>'); });
        }, 300);
    });

    $(document).on('click', '.pos-add-product', function() {
        var p = $(this).data('product');
        addToCart(p, 1);
    });

    $(document).on('change', '.pos-cart-qty', function() {
        var tr = $(this).closest('tr');
        var index = parseInt(tr.data('index'), 10);
        var item = cart[index];
        var val = parseInt($(this).val(), 10);
        if (isNaN(val) || val < 1) val = 1;
        if (val > item.stock_quantity) val = item.stock_quantity;
        item.quantity = val;
        $(this).val(val);
        tr.find('.pos-line-total').text('£' + formatMoney(item.unit_price * val));
        updateTotals();
    });

    $(document).on('click', '.pos-remove-item', function() {
        var index = parseInt($(this).closest('tr').data('index'), 10);
        cart.splice(index, 1);
        renderCart();
    });

    $('#pos-tax-rate, #pos-discount').on('input change', updateTotals);

    $('#pos-clear-cart').on('click', function() {
        if (cart.length && !confirm('Clear all items?')) return;
        cart = [];
        renderCart();
    });

    $('#pos-complete-sale').on('click', function() {
        if (cart.length === 0) {
            alert('Cart is empty.');
            return;
        }
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        var payload = {
            _token: token,
            items: cart.map(function(item) {
                return {
                    product_id: item.product_id,
                    quantity: item.quantity,
                    unit_price: item.unit_price
                };
            }),
            payment_method: $('#pos-payment-method').val(),
            customer_name: $('#pos-customer-name').val() || null,
            customer_phone: $('#pos-customer-phone').val() || null,
            customer_email: $('#pos-customer-email').val() || null,
            tax_rate: $('#pos-tax-rate').val() || 0,
            discount_amount: $('#pos-discount').val() || 0,
            notes: $('#pos-notes').val() || null
        };
        $.ajax({
            url: completeUrl,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .done(function(res) {
            if (res.success && res.sale) {
                cart = [];
                renderCart();
                $('#pos-discount').val(0);
                $('#pos-tax-rate').val(0);
                updateTotals();
                $('#pos-receipt-number').text(res.sale.sale_number);
                $('#pos-receipt-total').text(formatMoney(res.sale.total));
                $('#pos-receipt-modal').modal('show');
            }
        })
        .fail(function(xhr) {
            var msg = 'Sale failed.';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            alert(msg);
        })
        .always(function() {
            btn.prop('disabled', false).html('<i class="fa fa-check"></i> Complete sale');
        });
    });

    $('#pos-print-receipt').on('click', function() {
        window.print();
    });
})();
</script>
@endpush
