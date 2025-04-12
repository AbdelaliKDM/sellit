<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('asetts/css/bootstrap.css') }}">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('asetts/css/app.css') }}">

    <style>
        body {
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: transparent;
            padding: 25px;
            width: 450px;
            max-width: 95%;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('asetts/js/bootstrap.js') }}"></script>
</body>
</html>
