<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Helpers\SettingsHelper::getAppName() }}</title>

    <!-- Bootstrap 5 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('asetts/css/bootstrap.css') }}">
    <!-- Font Awesome for icons -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('asetts/css/font-awesome.css') }}">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('asetts/css/app.css') }}">

    <style>
        body {
            background-color: #e9ecef;
        }
        .content {
            background-color: transparent;
            padding: 20px;
            margin: 20px;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <a class="navbar-brand" href="#">{{ \App\Helpers\SettingsHelper::getAppName() }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item d-flex align-items-center me-2">
                            <span class="nav-link text-light">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.index') }}" title="{{ __('app.account_settings') }}">
                                <i class="fas fa-user-cog"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" title="{{ __('app.logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('home') ? 'active' : ''}}" href="{{route('home')}}">
                            <i class="fas fa-home"></i> {{ __('app.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('products.*') ? 'active' : ''}}" href="{{route('products.index')}}">
                            <i class="fas fa-cubes"></i> {{ __('app.products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('customers.*') ? 'active' : ''}}" href="{{route('customers.index')}}">
                            <i class="fas fa-user"></i> {{ __('app.customers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('pos.*') ? 'active' : ''}}" href="{{route('pos.index')}}">
                            <i class="fas fa-cash-register"></i> {{ __('app.pos') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('orders.*') ? 'active' : ''}}" href="{{route('orders.index')}}">
                            <i class="fas fa-shopping-cart"></i> {{ __('app.orders') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->route()->named('settings.*') ? 'active' : ''}}" href="{{route('settings.index')}}">
                            <i class="fas fa-cog"></i> {{ __('app.settings') }}
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar"></i> Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-question-circle"></i> Help
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <script src="{{ asset('asetts/js/bootstrap.js') }}"></script>
    <script src="{{ asset('asetts/js/jquery.js') }}"></script>
    @yield('scripts')
    <!-- Custom JavaScript -->
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const sidebar = document.querySelector('.sidebar');

            navbarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
