<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Voks Inn</title>

    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex flex-column flex-lg-row min-vh-100">
        <nav class="sidebar d-flex flex-column p-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="fas fa-hotel fa-lg text-white"></i>
                <span class="navbar-brand mb-0">Voks Inn</span>
            </div>
            <div class="d-flex flex-column gap-1">
                @php
                    $role = auth()->user()->role;
                    $isAdminLike = in_array($role, ['superadmin', 'admin']);
                    $isSuper = $role === 'superadmin';
                    $isHousekeeping = in_array($role, ['superadmin', 'housekeeper']);
                @endphp

                <a href="{{ route('dashboard.'.$role) }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                @if($isAdminLike)
                    <a href="{{ route('reservations.create') }}" class="nav-link {{ request()->routeIs('reservations.create') ? 'active' : '' }}">
                        <i class="fas fa-bed"></i> Check-In
                    </a>

                    <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.index') || request()->routeIs('reservations.show') ? 'active' : '' }}">
                        <i class="fas fa-door-open"></i> Check-Out
                    </a>

                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i> Keuangan
                    </a>
                @endif

                @if($isHousekeeping)
                    <a href="{{ route('housekeeping.index') }}" class="nav-link {{ request()->routeIs('housekeeping.*') ? 'active' : '' }}">
                        <i class="fas fa-broom"></i> Housekeeping
                    </a>
                @endif

                @if($isAdminLike)
                    <div class="mt-3 text-uppercase text-white-50 fw-semibold small">Laporan</div>
                    <a href="{{ route('reports.reservations') }}" class="nav-link {{ request()->routeIs('reports.reservations*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Reservasi
                    </a>
                    <a href="{{ route('reports.finance') }}" class="nav-link {{ request()->routeIs('reports.finance*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Keuangan
                    </a>
                    <a href="{{ route('reports.rooms') }}" class="nav-link {{ request()->routeIs('reports.rooms*') ? 'active' : '' }}">
                        <i class="fas fa-door-closed"></i> Kamar
                    </a>
                @endif

                @if($isSuper)
                    <div class="mt-3 text-uppercase text-white-50 fw-semibold small">Master Data</div>
                    <a href="{{ route('room-types.index') }}" class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Tipe Kamar
                    </a>
                    <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> Kamar
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Pengguna
                    </a>
                @endif
            </div>
        </nav>

        <div class="content-wrapper flex-grow-1">
            <header class="topbar d-flex flex-column flex-md-row align-items-md-center justify-content-between px-4 py-3 gap-2">
                <div>
                    <h5 class="mb-0 text-uppercase fw-semibold text-secondary">Selamat datang, {{ auth()->user()->name }}</h5>
                    <small class="text-muted">Role: {{ strtoupper(auth()->user()->role) }}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-cog"></i> Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="mt-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
