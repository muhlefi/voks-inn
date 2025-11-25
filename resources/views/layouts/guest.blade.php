<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Voks Inn</title>

    @vite(['resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="auth-background d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4">
                                <div class="display-6 text-primary fw-bold mb-2">
                                    <i class="fas fa-hotel me-2"></i>Voks Inn
                                </div>
                                <p class="text-muted mb-0">Manajemen Hotel - VOKS-INN</p>
                            </div>
                            @if (!empty($slot))
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
