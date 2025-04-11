@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>{{ __('app.dashboard') }}</h1>
        </div>
    </div>

    <!-- Count Stats Row (4 cards) -->
    <div class="row mb-4">
        <!-- Total Products -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-purple text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $totalProducts }}</div>
                            <div class="stat-title">{{ __('app.total_products') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-purple-dark text-center">
                    <a href="{{ route('products.index') }}" class="text-white text-decoration-none">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-blue text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $totalOrders }}</div>
                            <div class="stat-title">{{ __('app.total_orders') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-blue-dark text-center">
                    <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-red text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $totalCustomers }}</div>
                            <div class="stat-title">{{ __('app.total_customers') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-red-dark text-center">
                    <a href="{{ route('customers.index') }}" class="text-white text-decoration-none">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Today's Orders -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $todayOrders }}</div>
                            <div class="stat-title">{{ __('app.todays_orders') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary-dark text-center">
                    <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                        {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

        <!-- Amount Stats Row (2 cards per row) -->
        <div class="row mb-4">
            <!-- Total Income -->
            <div class="col-md-6 mb-3">
                <div class="card bg-green text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value">{{ $currencySymbol }}{{ number_format($totalIncome, 2) }}</div>
                                <div class="stat-title">{{ __('app.total_income') }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-green-dark text-center">
                        <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                            {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Profit -->
            <div class="col-md-6 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value">{{ $currencySymbol }}{{ number_format($totalProfit, 2) }}</div>
                                <div class="stat-title">{{ __('app.total_profit') }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-primary-dark text-center">
                        <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                            {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Today's Income -->
            <div class="col-md-6 mb-3">
                <div class="card bg-orange text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value">{{ $currencySymbol }}{{ number_format($todayIncome, 2) }}</div>
                                <div class="stat-title">{{ __('app.todays_income') }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-orange-dark text-center">
                        <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                            {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Profit -->
            <div class="col-md-6 mb-3">
                <div class="card bg-teal text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="stat-value">{{ $currencySymbol }}{{ number_format($todayProfit, 2) }}</div>
                                <div class="stat-title">{{ __('app.todays_profit') }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-teal-dark text-center">
                        <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                            {{ __('app.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
