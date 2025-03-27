<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        /* Styling Sidebar */
        .sidebar {
            width: 220px;
            background: #007bff; /* Biru Laut */
            color: white;
            padding: 15px;
            position: fixed;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar h3 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px;
            font-size: 14px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 8px;
            transition: 0.3s;
        }
        .sidebar a.active, .sidebar a:hover {
             background: rgba(255, 255, 255, 0.3);
        }
        .dropdown-container {
            display: none;
            padding-left: 20px;
        }
        .navbar {
            padding: 15px;
            background: #00aaff; /* Biru Langit */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 220px;
        }

        .navbar h4, .navbar span {
            color: white;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .dropdown-container {
            display: none; /* Default tertutup */
            padding-left: 20px;
        }

        .dropdown-container.show {
            display: block; /* Akan tetap terbuka */
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh; /* Pastikan sidebar penuh */
    }

    .menu {
        flex-grow: 1; /* Menu bisa memanjang sesuai ruang */
        overflow-y: auto; /* Scroll hanya jika menu terlalu panjang */
    }
    .footer {
        background: #00aaff; /* Biru Laut */
        color: white;
        padding: 10px;
        text-align: center;
    }

    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Lilik.Net</h3>
        <div class="admin-profile text-center my-3">
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="rounded-circle" width="70" height="70" alt="Foto Profil">
            <p class="mt-2 mb-0">{{ Auth::user()->nama_user }}</p>
            <small class="text-light">
                @if (Auth::check() && Auth::user()->level == 'admin')
                    Administrator
                @elseif (Auth::check())
                    Pelanggan
                @endif
            </small>                          
        </div>  
    
        <div class="menu">
            <a href="{{ route('beranda_admin') }}" class="menu-link {{ request()->routeIs('beranda_admin') ? 'active' : '' }}" data-title="Dashboard">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
    
            <a href="{{ route('pengguna.index') }}" class="menu-link {{ request()->routeIs('pengguna.index') ? 'active' : '' }}" data-title="Pengguna">
                <i class="fas fa-users me-2"></i>Pengguna
            </a>
    
            <a class="dropdown-toggle">
                <span><i class="fas fa-database me-2"></i>Data Master</span>
            </a>
            <div class="dropdown-container">
                <a href="{{ route('paket.index') }}" class="menu-link {{ request()->routeIs('paket.index') ? 'active' : '' }}" data-title="Paket">
                    <i class="fas fa-box me-2"></i>Data Paket
                </a>
                <a href="{{ route('pelanggan.index') }}" class="menu-link {{ request()->routeIs('pelanggan.index') ? 'active' : '' }}" data-title="Pelanggan">
                    <i class="fas fa-users me-2"></i>Data Pelanggan
                </a>
            </div>
    
            <a href="" class="menu-link">
                <i class="fas fa-exchange-alt me-2"></i>Kas Masuk dan Keluar
            </a>
    
            <a href="" class="menu-link">
                <i class="fas fa-money-bill-wave me-2"></i>Transaksi Pembayaran
            </a>
    
            <!-- Pindahin Laporan Kas Masuk dan Keluar di sini -->
            <a href="" class="menu-link">
                <i class="fas fa-file-alt me-2"></i>Laporan Kas Masuk dan Keluar
            </a>
        </div>
    </div>                

    <!-- Navbar -->
    <nav class="navbar">
        <h4 id="navbar-title">Dashboard</h4>
        <div>
            <span class="me-2">{{ Auth::user()->username }}</span>
            <form id="logout-form" action="{{ route('logout_admin') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>            
        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    <footer class="footer text-center mt-4">
        &copy; 2025 LILIK.NET | All Rights Reserved
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebarLinks = document.querySelectorAll(".menu-link");
        const navbarTitle = document.getElementById("navbar-title");
        const dropdownToggle = document.querySelector(".dropdown-toggle");
        const dropdownContainer = document.querySelector(".dropdown-container");

        // Ambil status menu aktif dari local storage
        const activeMenu = localStorage.getItem("activeMenu");
        const dropdownStatus = localStorage.getItem("dropdownOpen");

        if (activeMenu) {
            navbarTitle.textContent = activeMenu;
        }

        if (dropdownStatus === "open") {
            dropdownContainer.classList.add("show"); // Buka dropdown kalau sebelumnya terbuka
        }

        // Event listener untuk menu utama agar mengganti judul navbar
        sidebarLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            const selectedTitle = this.getAttribute("data-title") || this.textContent.trim();

            if (selectedTitle) {
                localStorage.setItem("activeMenu", selectedTitle);
            }
        });
    });
        // Event listener untuk dropdown toggle
        dropdownToggle.addEventListener("click", function (event) {
            event.preventDefault();
            dropdownContainer.classList.toggle("show");

            // Simpan status dropdown ke local storage
            if (dropdownContainer.classList.contains("show")) {
                localStorage.setItem("dropdownOpen", "open");
            } else {
                localStorage.setItem("dropdownOpen", "closed");
            }
        });
    });
    </script>
</body>
</html>
