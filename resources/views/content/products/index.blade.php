@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2>{{ __('app.products') }}</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('app.add_new_product') }}
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('app.search') }} {{ __('app.by_name_or_barcode') }}" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('app.all_statuses') }}</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('app.available') }}</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>{{ __('app.unavailable') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="discount" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('app.all_products') }}</option>
                        <option value="active" {{ request('discount') == 'active' ? 'selected' : '' }}>{{ __('app.with_active_discount') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    @if(request('search') || request('status') || request('discount'))
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('app.clear_search') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('app.product_image') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.barcode') }}</th>
                            <th>{{ __('app.purchase_price') }}</th>
                            <th>{{ __('app.selling_price') }}</th>
                            <th>{{ __('app.quantity') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.discount') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" height="50" class="img-thumbnail">
                                @else
                                <span class="text-muted">{{ __('app.no_image') }}</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>{{ number_format($product->purchase_price, 2) }} {{ $currencySymbol }}</td>
                            <td>
                                @if($product->hasActiveDiscount())
                                <span class="text-decoration-line-through">{{ number_format($product->selling_price, 2) }} {{ $currencySymbol }}</span>
                                <span class="text-danger">{{ number_format($product->getCurrentPrice(), 2) }} {{ $currencySymbol }}</span>
                                @else
                                {{ number_format($product->selling_price, 2) }} {{ $currencySymbol }}
                                @endif
                            </td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <span class="badge bg-{{ $product->status === 'available' ? 'success' : 'danger' }}">
                                    {{ $product->status === 'available' ? __('app.available') : __('app.unavailable') }}
                                </span>
                            </td>
                            <td>
                                @if($discount = $product->activeDiscount())
                                <span class="badge bg-warning text-dark">
                                    {{ $discount->starts_at->format('M d') }} - {{ $discount->ends_at->format('M d') }}
                                </span>
                                <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Replace the discount delete form with button -->
                                <button type="button" class="btn btn-sm btn-outline-danger delete-discount-btn" data-discount-id="{{ $discount->id }}">
                                    <i class="fas fa-times"></i>
                                </button>

                                <!-- Update the scripts section -->
                                @section('scripts')
                                <script>
                                    $(document).ready(function() {
                                        let productToDelete = null;
                                        let discountToDelete = null;

                                        // Show delete confirmation modal
                                        $('.delete-product-btn').on('click', function(e) {
                                            e.preventDefault();
                                            productToDelete = $(this).data('product-id');
                                            $('#deleteProductModal').modal('show');
                                        });

                                        // Handle delete confirmation
                                        $('#confirm-delete-btn').on('click', function() {
                                            if (productToDelete) {
                                                const form = $('#delete-product-form');
                                                form.attr('action', `/products/${productToDelete}`);
                                                form.submit();
                                            }
                                            $('#deleteProductModal').modal('hide');
                                        });

                                        // Show discount delete confirmation modal
                                        $('.delete-discount-btn').on('click', function(e) {
                                            e.preventDefault();
                                            discountToDelete = $(this).data('discount-id');
                                            $('#deleteDiscountModal').modal('show');
                                        });

                                        // Handle discount delete confirmation
                                        $('#confirm-discount-delete-btn').on('click', function() {
                                            if (discountToDelete) {
                                                const form = $('#delete-discount-form');
                                                form.attr('action', `/discounts/${discountToDelete}`);
                                                form.submit();
                                            }
                                            $('#deleteDiscountModal').modal('hide');
                                        });
                                    });
                                </script>
                                @endsection
                                @else
                                <a href="{{ route('products.discounts.create', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-tag"></i> {{ __('app.add') }}
                                </a>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-product-btn" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">{{ __('app.no_products_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="deleteProductModalLabel">{{ __('app.confirm_delete_product') }}</h5>
                <button type="button" class="btn-close ms-0 me-n2" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.confirm_delete_product_message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.no') }}</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">{{ __('app.yes') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete submission -->
<form id="delete-product-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Discount Confirmation Modal -->
<div class="modal fade" id="deleteDiscountModal" tabindex="-1" aria-labelledby="deleteDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justi">
                <h5 class="modal-title" id="deleteDiscountModalLabel">{{ __('app.confirm_remove_discount') }}</h5>
                <button type="button" class="btn-close ms-0 me-n2" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.confirm_remove_discount_message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.no') }}</button>
                <button type="button" class="btn btn-danger" id="confirm-discount-delete-btn">{{ __('app.yes') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for discount delete submission -->
<form id="delete-discount-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let productToDelete = null;

        // Show delete confirmation modal
        $('.delete-product-btn').on('click', function(e) {
            e.preventDefault();
            productToDelete = $(this).data('product-id');
            $('#deleteProductModal').modal('show');
        });

        // Handle delete confirmation
        $('#confirm-delete-btn').on('click', function() {
            if (productToDelete) {
                const form = $('#delete-product-form');
                form.attr('action', `/products/${productToDelete}`);
                form.submit();
            }
            $('#deleteProductModal').modal('hide');
        });
    });
</script>
@endsection
