@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('app.edit_discount_for') }} {{ $product->name }}</h2>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('app.back') }} {{ __('app.to_products') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>{{ __('app.product_details') }}</h5>
                    <p><strong>{{ __('app.name') }}:</strong> {{ $product->name }}</p>
                    <p><strong>{{ __('app.barcode') }}:</strong> {{ $product->barcode }}</p>
                    <p><strong>{{ __('app.regular_price') }}:</strong> {{ $currencySymbol }}{{ number_format($product->selling_price, 2) }}</p>
                </div>
                @if($product->image)
                <div class="col-md-6 text-center">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 150px;">
                </div>
                @endif
            </div>

            <form action="{{ route('discounts.update', $discount) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="starts_at" class="form-label">{{ __('app.start_date') }}</label>
                        <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at', $discount->starts_at->format('Y-m-d\TH:i')) }}" required>
                        @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ends_at" class="form-label">{{ __('app.end_date') }}</label>
                        <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at" value="{{ old('ends_at', $discount->ends_at->format('Y-m-d\TH:i')) }}" required>
                        @error('ends_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="new_price" class="form-label">{{ __('app.discounted_price') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ $currencySymbol }}</span>
                        <input type="number" step="0.01" class="form-control @error('new_price') is-invalid @enderror" id="new_price" name="new_price" value="{{ old('new_price', $discount->new_price) }}" required>
                    </div>
                    <small class="text-muted">{{ __('app.must_be_less_than_regular', ['price' => $currencySymbol.number_format($product->selling_price, 2)]) }}</small>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('app.update_discount') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
