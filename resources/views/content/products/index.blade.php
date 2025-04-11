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
                            <td>{{ $currencySymbol }}{{ number_format($product->purchase_price, 2) }}</td>
                            <td>
                                @if($product->hasActiveDiscount())
                                <span class="text-decoration-line-through">{{ $currencySymbol }}{{ number_format($product->selling_price, 2) }}</span>
                                <span class="text-danger">{{ $currencySymbol }}{{ number_format($product->getCurrentPrice(), 2) }}</span>
                                @else
                                {{ $currencySymbol }}{{ number_format($product->selling_price, 2) }}
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
                                <form action="{{ route('discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('app.confirm_remove_discount') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
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
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('app.confirm_delete_product') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
@endsection
