@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2>{{ __('app.orders') }}</h2>
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <i class="fas fa-cash-register"></i> {{ __('app.new_order_pos') }}
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('app.total_amount') }}</h6>
                            <h2 class="mt-2 mb-0">{{ number_format($orders->sum('total_amount'), 2) }} {{ $currencySymbol }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('app.total_remaining') }}</h6>
                            <h2 class="mt-2 mb-0">{{ number_format($orders->sum('remaining_amount'), 2) }} {{ $currencySymbol }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-hand-holding-usd fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('app.total_profit') }}</h6>
                            <h2 class="mt-2 mb-0">{{ number_format($orders->sum('total_profit'), 2) }} {{ $currencySymbol }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label for="search" class="form-label">{{ __('app.order_id') }}</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('app.status') }}</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">{{ __('app.all_statuses') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="customer_id" class="form-label">{{ __('app.customer') }}</label>
                    <select class="form-select" id="customer_id" name="customer_id">
                        <option value="">{{ __('app.all_customers') }}</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">{{ __('app.date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">{{ __('app.date_to') }}</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> {{ __('app.filter') }}
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'customer_id', 'date_from', 'date_to']))
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('app.clear') }}
                        </a>
                        @endif
                    </div>
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
                            <th>{{ __('app.order_id') }}</th>
                            <th>{{ __('app.customer') }}</th>
                            <th>{{ __('app.total_amount') }}</th>
                            <th>{{ __('app.paid') }}</th>
                            <th>{{ __('app.remaining') }}</th>
                            <th>{{ __('app.profit') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.date') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @if($order->customer)
                                <a href="{{ route('customers.show', $order->customer) }}">{{ $order->customer->name }}</a>
                                @else
                                <span class="text-muted">{{ __('app.walk_in_customer') }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($order->total_amount, 2) }} {{ $currencySymbol }}</td>
                            <td>{{ number_format($order->paid_amount, 2) }} {{ $currencySymbol }}</td>
                            <td>
                                @if($order->remaining_amount > 0)
                                <span class="text-danger">{{ number_format($order->remaining_amount, 2) }} {{ $currencySymbol }}</span>
                                @else
                                <span class="text-success">0.00 {{ $currencySymbol }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($order->total_profit, 2) }} {{ $currencySymbol }}</td>
                            <td>
                                @if($order->status == 'completed')
                                <span class="badge bg-success">{{ __('app.completed') }}</span>
                                @elseif($order->status == 'pending')
                                <span class="badge bg-warning text-dark">{{ __('app.pending') }}</span>
                                @else
                                <span class="badge bg-danger">{{ __('app.cancelled') }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('app.no_orders_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
