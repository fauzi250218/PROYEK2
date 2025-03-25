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
            background: #2c3e50;
            color: white;
            padding: 15px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
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
            background: rgba(255, 255, 255, 0.2);
        }
        .dropdown-container {
            display: none;
            padding-left: 20px;
        }
        .navbar {
            padding: 15px;
            background: white;
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 220px;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Lilik.Net</h3>

        <!-- Ikon Admin & Nama -->
        <div class="admin-profile text-center my-3">
            <i class="fas fa-user-circle fa-3x"></i>
            <p class="mt-2 mb-0">{{ Auth::user()->nama_user }}</p>
            <small class="text-light">
                @if ($user->level == 'admin')
                    Administrator
                @else
                    Pelanggan
                @endif
            </small>            
        </div>

        <a href="{{ route('beranda_admin') }}" class="menu-link {{ request()->routeIs('beranda_admin') ? 'active' : '' }}" data-title="Dashboard">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>

        <a href="" class="menu-link" data-title="Setting">
            <i class="fas fa-cogs me-2"></i>Setting
        </a>

        <a href="{{ route('pengguna.index') }}" class="menu-link" data-title="Pengguna">
            <i class="fas fa-users me-2"></i>Pengguna
        </a>

        <!-- Dropdown Data Master -->
        <a class="dropdown-toggle">
            <span><i class="fas fa-database me-2"></i>Data Master</span>
        </a>
        <div class="dropdown-container">
            <a href="{{ route('paket.index') }}" class="menu-link {{ request()->routeIs('paket.index') ? 'active' : '' }}" data-title="Data Paket">
                <i class="fas fa-box me-2"></i>Data Paket
            </a>
            <a href="" class="menu-link" data-title="Data Pelanggan">
                <i class="fas fa-users me-2"></i>Data Pelanggan
            </a>
        </div>

        <a href="" class="menu-link" data-title="Kas Masuk dan Keluar">
            <i class="fas fa-exchange-alt me-2"></i>Kas Masuk dan Keluar
        </a>

        <a href="" class="menu-link" data-title="Transaksi Pembayaran">
            <i class="fas fa-money-bill-wave me-2"></i>Transaksi Pembayaran
        </a>

        <a href="" class="menu-link" data-title="Laporan Kas">
            <i class="fas fa-file-alt me-2"></i>Laporan Kas Masuk dan Keluar
        </a>
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

            // Ambil status menu aktif dari local storage
            const activeMenu = localStorage.getItem("activeMenu");

            if (activeMenu) {
                navbarTitle.textContent = activeMenu;
            }

            // Event listener untuk mengganti navbar title tanpa menghalangi navigasi
            sidebarLinks.forEach(link => {
                link.addEventListener("click", function () {
                    const selectedTitle = this.getAttribute("data-title");

                    // Ganti judul navbar
                    navbarTitle.textContent = selectedTitle;

                    // Simpan menu aktif ke local storage
                    localStorage.setItem("activeMenu", selectedTitle);
                });
            });

            // Dropdown toggle
            const dropdownToggle = document.querySelector(".dropdown-toggle");
            const dropdownContainer = document.querySelector(".dropdown-container");

            dropdownToggle.addEventListener("click", function (event) {
                event.preventDefault();
                dropdownContainer.style.display = (dropdownContainer.style.display === "block") ? "none" : "block";
            });
        });
    </script>
</body>
</html>
