@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('app.order_details', ['id' => $order->id]) }}</h2>
                <div>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('app.back_to_orders') }}
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> {{ __('app.print') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> {{ __('app.order_items') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('app.product') }}</th>
                                    <th width="15%">{{ __('app.price') }}</th>
                                    <th width="15%">{{ __('app.quantity') }}</th>
                                    <th width="20%">{{ __('app.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->image)
                                            <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%;">
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                            </div>
                                            @else
                                            <div class="bg-light text-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                                <i class="fas fa-box text-secondary"></i>
                                            </div>
                                            @endif
                                            <div class="ms-2">
                                                <div class="fw-bold">{{ $item->name }}</div>
                                                @if($item->barcode)
                                                <small class="text-muted">{{ $item->barcode }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 2) }} {{ $currencySymbol }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price * $item->quantity, 2) }} {{ $currencySymbol }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">{{ __('app.total') }}:</td>
                                    <td class="fw-bold">{{ number_format($order->total_amount, 2) }} {{ $currencySymbol }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> {{ __('app.order_information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.status') }}:</div>
                        <div>
                            @if($order->status == 'completed')
                            <span class="badge bg-success">{{ __('app.completed') }}</span>
                            @elseif($order->status == 'pending')
                            <span class="badge bg-warning text-dark">{{ __('app.pending') }}</span>
                            @else
                            <span class="badge bg-danger">{{ __('app.cancelled') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.date') }}:</div>
                        <div>{{ $order->created_at->format('F d, Y H:i:s') }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.customer') }}:</div>
                        <div>
                            @if($order->customer)
                            <a href="{{ route('customers.show', $order->customer) }}">{{ $order->customer->name }}</a>
                            @else
                            <span class="text-muted">{{ __('app.walk_in_customer') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.total_amount') }}:</div>
                        <div class="fs-5">{{ number_format($order->total_amount, 2) }} {{ $currencySymbol }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.total_paid') }}:</div>
                        <div class="fs-5">{{ number_format($order->paid_amount, 2) }} {{ $currencySymbol }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold">{{ __('app.remaining') }}:</div>
                        <div class="fs-5 {{ $order->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($order->remaining_amount, 2) }} {{ $currencySymbol }}
                        </div>
                    </div>

                    @if($order->status == 'pending')
                    <hr>

                    <form action="{{ route('orders.update-payment', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">{{ __('app.update_payment') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="paid_amount" name="paid_amount" value="{{ $order->paid_amount }}" min="0" step="0.01" required>
                                <span class="input-group-text">{{ $currencySymbol }}</span>
                                <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> {{ __('app.actions') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('app.change_status') }}</label>
                            <div class="input-group">
                                <select class="form-select" id="status" name="status" required>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                                </select>
                                <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    @media print {
        .navbar, .sidebar, .btn, form, .card-header, footer {
            display: none !important;
        }
        .content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
    }
</style>
@endsection
