
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Sistem Pengiriman Unit Gudang Cimanggis 2')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    @stack('styles')
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            color: #1a1a1a;
        }
        .wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 260px;
            background-color: #0d1b2a;
            color: #fff;
            padding: 25px 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar.collapsed {
            width: 80px;
            padding: 25px 10px;
        }
        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            padding: 30px;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            background-color: #f5f7fa;
        }
        .collapsed + .main-content {
            margin-left: 80px;
        }
        .toggle-btn {
            text-align: right;
            margin-bottom: 20px;
        }
        .toggle-btn button {
            border: none;
            background: none;
            font-size: 20px;
            color: #ccc;
        }
        .user-info {
            text-align: center;
            font-size: 14px;
            margin-bottom: 25px;
            border-bottom: 1px solid #3b4c60;
            padding-bottom: 15px;
            position: relative;
        }
        .user-info img, .user-info i {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 8px;
        }
        .user-info i {
            font-size: 48px;
            color: #fff;
        }
        .user-info strong, .user-info small {
            display: block;
            color: #fff;
        }
        .sidebar a {
            color: #d6d6d6;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .sidebar a:hover {
            background-color: #1c2b3a;
            color: #ffffff;
        }
        .sidebar a.active {
            background-color: #1c2b3a;
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
        }
        .sidebar a span {
            transition: 0.3s;
        }
        .sidebar.collapsed a span {
            display: none;
        }
        .flash-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="toggle-btn">
            <button onclick="toggleSidebar()">
                <i class="bi bi-chevron-left" id="toggle-icon"></i>
            </button>
        </div>

        @auth
        <div class="user-info">
            @if(Auth::user()->type != 1)
                <a href="{{ route('profile.edit') }}" class="edit-btn" title="Edit Profil" style="position:absolute; top:5px; right:5px;">
                    <i class="bi bi-pencil-square text-info"></i>
                </a>
            @endif
            @if (Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto Profil">
            @else
                <i class="bi bi-person-circle"></i>
            @endif
            <strong>{{ Auth::user()->name }}</strong>
            <small>{{ Auth::user()->email }}</small>
        </div>
        @endauth

        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>

        <a href="{{ route('requests.create') }}" class="{{ request()->routeIs('requests.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i><span>Tambah Permintaan</span>
        </a>

        @auth
        @if(Auth::user()->type == 1)
            <a href="{{ route('datarekaps.create') }}" class="{{ request()->routeIs('datarekaps.create') ? 'active' : '' }}">
                <i class="bi bi-journal-plus"></i><span>Tambah Data Rekap</span>
            </a>
            <a href="{{ route('slot-deliveries.create') }}" class="{{ request()->routeIs('slot-deliveries.create') ? 'active' : '' }}">
                <i class="bi bi-plus-square-dotted"></i><span>Tambah Slot</span>
            </a>
        @endif
        @endauth

        <a href="{{ route('requests.index') }}" class="{{ request()->routeIs('requests.index') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i><span>Data Permintaan</span>
        </a>
        <a href="{{ route('datarekaps.index') }}" class="{{ request()->routeIs('datarekaps.index') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i><span>Data Rekap</span>
        </a>
        <a href="{{ route('slot-deliveries.index') }}" class="{{ request()->routeIs('slot-deliveries.index') ? 'active' : '' }}">
            <i class="bi bi-truck"></i><span>Slot Pengiriman</span>
        </a>

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i><span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert" id="flash-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(() => {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 4000);

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('toggle-icon');
        sidebar.classList.toggle('collapsed');
        icon.classList.toggle('bi-chevron-left');
        icon.classList.toggle('bi-chevron-right');
    }
</script>
@stack('scripts')
</body>
</html>