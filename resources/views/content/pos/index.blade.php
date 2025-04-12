@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2>{{ __('app.point_of_sale') }}</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> {{ __('app.view_orders') }}
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Side - Cart -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="barcode-input" class="form-control"
                                        placeholder="{{ __('app.scan_barcode') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="barcode-btn">
                                        <i class="fas fa-barcode"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select id="customer-select" class="form-select">
                                    <option value="">{{ __('app.walking_customer') }}</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="cart-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('app.product') }}</th>
                                        <th width="15%">{{ __('app.quantity') }}</th>
                                        <th width="15%">{{ __('app.price') }}</th>
                                        <th width="20%">{{ __('app.total') }}</th>
                                        <th width="5%">{{ __('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="empty-cart-row">
                                        <td colspan="5" class="text-center py-4">{{ __('app.no_items_in_cart') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <label for="paid-amount" class="form-label">{{ __('app.paid_amount') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="paid-amount" value="0.00"
                                                min="0" step="0.01">
                                            <span class="input-group-text">{{ $currencySymbol }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="remaining-amount" class="form-label">{{ __('app.remaining_amount') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="remaining-amount" value="0.00"
                                                readonly>
                                            <span class="input-group-text">{{ $currencySymbol }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <h3 class="mb-2">{{ __('app.total') }}</h3>
                                <h1 class="display-4 fw-bold text-primary mb-3" id="total-amount">0.00 {{ $currencySymbol }}</h1>
                                <div class="d-grid gap-2">
                                    <button id="process-order-btn" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle"></i> {{ __('app.process_order') }}
                                    </button>
                                    <button id="cancel-order-btn" class="btn btn-danger">
                                        <i class="fas fa-times-circle"></i> {{ __('app.cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Products -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" id="product-search" class="form-control" placeholder="{{ __('app.search_product') }}">
                            <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#quickAddModal">
                                <i class="fas fa-plus"></i> {{ __('app.quick_add') }}
                            </button>
                        </div>

                        <div id="products-container" class="row row-cols-2 g-2">
                            @foreach ($products as $product)
                                <div class="col product-item" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->getCurrentPrice() }}" data-barcode="{{ $product->barcode }}">
                                    <div class="card h-100 product-card">
                                        <div class="position-relative">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                                    alt="{{ $product->name }}" style="height: 100px; object-fit: cover;">
                                            @else
                                                <div class="bg-light text-center py-4">
                                                    <i class="fas fa-box fa-3x text-secondary"></i>
                                                </div>
                                            @endif
                                            @if ($product->hasActiveDiscount())
                                                <span class="position-absolute top-0 end-0 badge bg-danger">{{ __('app.sale') }}</span>
                                            @endif
                                        </div>
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1 text-truncate">{{ $product->name }}</h6>
                                            <p class="card-text mb-0">
                                                @if ($product->hasActiveDiscount())
                                                    <span class="text-decoration-line-through text-muted small">{{ number_format($product->selling_price, 2) }} {{ $currencySymbol }}</span>
                                                    <span class="text-danger fw-bold">{{ number_format($product->getCurrentPrice(), 2) }} {{ $currencySymbol }}</span>
                                                @else
                                                    <span class="fw-bold">{{ number_format($product->selling_price, 2) }} {{ $currencySymbol }}</span>
                                                @endif
                                            </p>
                                            <small class="text-muted">{{ __('app.stock') }}: {{ $product->quantity }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Product Modal -->
    <div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickAddModalLabel">{{ __('app.quick_add_product') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
                </div>
                <div class="modal-body">
                    <form id="quick-add-form">
                        <div class="mb-3">
                            <label for="product-name" class="form-label">{{ __('app.product_name') }}</label>
                            <input type="text" class="form-control" id="product-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="product-barcode" class="form-label">{{ __('app.barcode_optional') }}</label>
                            <input type="text" class="form-control" id="product-barcode">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase-price" class="form-label">{{ __('app.purchase_price') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currencySymbol }}</span>
                                    <input type="number" class="form-control" id="purchase-price" min="0"
                                        step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selling-price" class="form-label">{{ __('app.selling_price') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currencySymbol }}</span>
                                    <input type="number" class="form-control" id="selling-price" min="0"
                                        step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="product-quantity" class="form-label">{{ __('app.quantity') }}</label>
                            <input type="number" class="form-control" id="product-quantity" min="1"
                                value="1" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="save-product-btn">{{ __('app.save_add_to_cart') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Cart management
            let cart = [];
            const $cartTable = $('#cart-table');
            const $emptyCartRow = $('#empty-cart-row');
            const $totalAmountDisplay = $('#total-amount');
            const $paidAmountInput = $('#paid-amount');
            const $remainingAmountInput = $('#remaining-amount');
            const $processOrderBtn = $('#process-order-btn');
            const $cancelOrderBtn = $('#cancel-order-btn');
            const $customerSelect = $('#customer-select');

            // Barcode scanning
            const $barcodeInput = $('#barcode-input');
            const $barcodeBtn = $('#barcode-btn');

            // Product search
            const $productSearch = $('#product-search');
            const $searchBtn = $('#search-btn');
            const $productsContainer = $('#products-container');

            // Quick add product
            const $quickAddForm = $('#quick-add-form');
            const $saveProductBtn = $('#save-product-btn');

            // Flag to track if paid amount was manually changed
            let paidAmountManuallyChanged = false;

            // Add product to cart
            function addToCart(product, quantity = 1, fromBarcode = false) {
                // Check if product already in cart
                const existingItemIndex = cart.findIndex(item => item.id === product.id);

                if (existingItemIndex !== -1) {
                    // Product exists in cart, increment quantity
                    cart[existingItemIndex].quantity += quantity;
                    cart[existingItemIndex].total = cart[existingItemIndex].price * cart[existingItemIndex].quantity;
                    updateCartDisplay(fromBarcode ? null : existingItemIndex); // Only focus on quantity if not from barcode
                } else {
                    // New product, add to cart
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        quantity: quantity,
                        total: parseFloat(product.price) * quantity,
                        image: product.image,
                        barcode: product.barcode
                    });
                    updateCartDisplay(fromBarcode ? null : cart.length - 1); // Only focus on quantity if not from barcode
                }

                // If added from barcode, focus back on barcode input
                if (fromBarcode) {
                    setTimeout(function() {
                        $barcodeInput.focus();
                    }, 100);
                }
            }

            // Update cart display
            function updateCartDisplay(focusIndex = null) {
                // Clear existing rows except header
                const $tbody = $cartTable.find('tbody');
                $tbody.empty();

                if (cart.length === 0) {
                    $tbody.append($emptyCartRow);
                    $totalAmountDisplay.text(`0.00 {{ $currencySymbol }}`);
                    $paidAmountInput.val('0.00');
                    $remainingAmountInput.val('0.00');
                    paidAmountManuallyChanged = false;
                    return;
                }

                let total = 0;
                let $quantityInputToFocus = null;

                // Add cart items
                $.each(cart, function(index, item) {
                    const imageHtml = item.image ?
                        `<img src="${item.image.startsWith('http') ? item.image : '/storage/' + item.image}" alt="${item.name}" width="40" height="40" class="img-thumbnail">` :
                        `<div class="bg-light text-center" style="width: 40px; height: 40px;"><i class="fas fa-box text-secondary"></i></div>`;

                    const $row = $('<tr>');

                    // Product info cell
                    const $productCell = $('<td>').html(`
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                ${imageHtml}
                            </div>
                            <div class="ms-2">
                                <div class="fw-bold">${item.name}</div>
                                <small class="text-muted">${item.barcode || '{{ __('app.no_barcode') }}'}</small>
                            </div>
                        </div>
                    `);

                    // Quantity cell with input
                    const $quantityCell = $('<td>');
                    const $quantityInput = $('<input>', {
                        type: 'number',
                        class: 'form-control quantity-input',
                        value: item.quantity,
                        min: 1,
                        'data-index': index
                    }).on('change', function() {
                        updateQuantity(index, parseInt($(this).val()) || 1);
                    });
                    $quantityCell.append($quantityInput);

                    // Store reference to input if this is the one to focus and focusIndex is not null
                    if (focusIndex !== null && focusIndex === index) {
                        $quantityInputToFocus = $quantityInput;
                    }

                    // Price cell
                    const $priceCell = $('<td>').text(`${item.price.toFixed(2)} {{ $currencySymbol }}`);

                    // Total cell
                    const $totalCell = $('<td>').addClass('fw-bold').text(`${item.total.toFixed(2)} {{ $currencySymbol }}`);

                    // Action cell with remove button
                    const $actionCell = $('<td>');
                    const $removeBtn = $('<button>', {
                        class: 'btn btn-sm btn-danger',
                        html: '<i class="fas fa-trash"></i>'
                    }).on('click', function() {
                        removeFromCart(index);
                    });
                    $actionCell.append($removeBtn);

                    // Add cells to row
                    $row.append($productCell, $quantityCell, $priceCell, $totalCell, $actionCell);

                    // Add row to table
                    $tbody.append($row);

                    // Add to total
                    total += item.total;
                });

                // Update total display
                $totalAmountDisplay.text(`${total.toFixed(2)} {{ $currencySymbol }}`);

                // If paid amount hasn't been manually changed, set it equal to total
                if (!paidAmountManuallyChanged) {
                    $paidAmountInput.val(total.toFixed(2));
                    $remainingAmountInput.val('0.00');
                } else {
                    // If it was manually changed, recalculate the remaining
                    const paid = parseFloat($paidAmountInput.val()) || 0;
                    $remainingAmountInput.val(Math.max(0, total - paid).toFixed(2));
                }

                // Focus on the quantity input if specified and not null
                if ($quantityInputToFocus) {
                    setTimeout(function() {
                        $quantityInputToFocus.focus().select();
                    }, 100);
                }
            }

            // Update quantity of cart item
            function updateQuantity(index, newQuantity) {
                if (index >= 0 && index < cart.length) {
                    // Ensure quantity is at least 1
                    newQuantity = Math.max(1, newQuantity);

                    // Update quantity and recalculate total
                    cart[index].quantity = newQuantity;
                    cart[index].total = cart[index].price * newQuantity;

                    // Update display
                    updateCartDisplay();
                }
            }

            // Remove item from cart
            function removeFromCart(index) {
                if (index >= 0 && index < cart.length) {
                    cart.splice(index, 1);
                    updateCartDisplay();
                }
            }
            // Handle barcode scanning
            $barcodeBtn.on('click', function() {
                const barcode = $barcodeInput.val().trim();
                if (barcode) {
                    $.ajax({
                        url: `/pos/product-by-barcode?barcode=${barcode}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.error) {
                                alert('Product not found');
                            } else {
                                addToCart(data, 1, true); // Pass true to indicate from barcode
                                $barcodeInput.val('');
                            }
                        },
                        error: function(error) {
                            console.error('Error:', error);
                            alert('Error fetching product');
                        }
                    });
                }
            });

            $barcodeInput.on('keypress', function(e) {
                if (e.which === 13) {
                    $barcodeBtn.click();
                    e.preventDefault();
                }
            });

            // Handle product search
            $searchBtn.on('click', function() {
                const query = $productSearch.val().trim();
                if (query) {
                    $.ajax({
                        url: `/pos/search-products?query=${query}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            updateProductsDisplay(data);
                        },
                        error: function(error) {
                            console.error('Error:', error);
                            alert('Error searching products');
                        }
                    });
                }
            });

            $productSearch.on('keypress', function(e) {
                if (e.which === 13) {
                    $searchBtn.click();
                    e.preventDefault();
                }
            });

            // Update products display
            function updateProductsDisplay(products) {
                $productsContainer.empty();

                if (products.length === 0) {
                    $productsContainer.html('<div class="col-12 text-center py-4">No products found</div>');
                    return;
                }

                $.each(products, function(index, product) {
                    const $productDiv = $('<div>', {
                        class: 'col product-item',
                        'data-id': product.id,
                        'data-name': product.name,
                        'data-price': product.selling_price,
                        'data-barcode': product.barcode || ''
                    });

                    const imageHtml = product.image ?
                        `<img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="height: 100px; object-fit: cover;">` :
                        `<div class="bg-light text-center py-4"><i class="fas fa-box fa-3x text-secondary"></i></div>`;

                    $productDiv.html(`
                        <div class="card h-100 product-card">
                            <div class="position-relative">
                                ${imageHtml}
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 text-truncate">${product.name}</h6>
                                <p class="card-text mb-0">
                                    <span class="fw-bold">${parseFloat(product.selling_price).toFixed(2)} {{ $currencySymbol }}</span>
                                </p>
                                <small class="text-muted">Stock: ${product.quantity}</small>
                            </div>
                        </div>
                    `);

                    $productsContainer.append($productDiv);
                });
            }

            // Add click event to product items
            $(document).on('click', '.product-item', function() {
                const $this = $(this);
                const productData = {
                    id: $this.data('id'),
                    name: $this.data('name'),
                    price: $this.data('price'),
                    barcode: $this.data('barcode'),
                    image: $this.find('img').attr('src')
                };
                addToCart(productData);
            });

            // Handle paid amount change
            $paidAmountInput.on('input', function() {
                paidAmountManuallyChanged = true;

                if (cart.length > 0) {
                    const total = calculateTotal();
                    const paid = parseFloat($(this).val()) || 0;
                    $remainingAmountInput.val(Math.max(0, total - paid).toFixed(2));
                }
            });

            // Calculate total amount from cart items
            function calculateTotal() {
                return cart.reduce((sum, item) => sum + item.total, 0);
            }

            // Handle quick add product
            $saveProductBtn.on('click', function() {
                const name = $('#product-name').val().trim();
                const barcode = $('#product-barcode').val().trim();
                const purchasePrice = parseFloat($('#purchase-price').val()) || 0;
                const sellingPrice = parseFloat($('#selling-price').val()) || 0;
                const quantity = parseInt($('#product-quantity').val()) || 1;

                if (!name || purchasePrice <= 0 || sellingPrice <= 0) {
                    alert('Please fill all required fields');
                    return;
                }

                // Send request to create product
                $.ajax({
                    url: '/pos/quick-add-product',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name: name,
                        barcode: barcode,
                        purchase_price: purchasePrice,
                        selling_price: sellingPrice,
                        quantity: quantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        // Add to cart
                        const productData = {
                            id: data.id,
                            name: data.name,
                            price: data.selling_price,
                            barcode: data.barcode,
                            image: data.image
                        };
                        addToCart(productData);

                        // Close modal and reset form
                        $('#quickAddModal').modal('hide');
                        $quickAddForm[0].reset();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        alert('Error adding product');
                    }
                });
            });

            // Process order
            $processOrderBtn.on('click', function() {
                if (cart.length === 0) {
                    alert('Please add items to cart');
                    return;
                }

                const customerId = $customerSelect.val() || null;
                const totalAmount = calculateTotal();
                const paidAmount = parseFloat($paidAmountInput.val()) || 0;

                if (paidAmount <= 0) {
                    alert('Please enter paid amount');
                    return;
                }

                // Prepare order data
                const orderData = {
                    customer_id: customerId,
                    items: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity
                    })),
                    total_amount: totalAmount,
                    paid_amount: paidAmount,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // Send request to process order
                $.ajax({
                    url: '/pos/process-order',
                    type: 'POST',
                    dataType: 'json',
                    data: orderData,
                    success: function(data) {
                        if (data.success) {
                            // Show success message
                            alert('Order processed successfully!');

                            // Clear cart
                            cart = [];
                            updateCartDisplay();

                            // Redirect to order details or print receipt
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error processing order');
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        alert('Error processing order');
                    }
                });
            });

            // Cancel order
            $cancelOrderBtn.on('click', function() {
                if (confirm('Are you sure you want to cancel this order?')) {
                    cart = [];
                    updateCartDisplay();
                }
            });
        });
    </script>
@endsection
