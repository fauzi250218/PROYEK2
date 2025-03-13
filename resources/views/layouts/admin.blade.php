<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f0f4f8;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }
        .sidebar h3 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-size: 16px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        .navbar {
            margin-left: 250px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h4 {
            color: #34495e;
            font-weight: bold;
        }
        .navbar .btn-danger {
            background: #dc3545;
            border: none;
            transition: 0.3s;
        }
        .navbar .btn-danger:hover {
            background: #bb2d3b;
        }
        .footer {
            margin-left: 250px;
            background: #34495e;
            color: white;
            text-align: center;
            padding: 12px;
            position: fixed;
            bottom: 0;
            width: calc(100% - 250px);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Lilik Net</h3>
        <a href="#" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="#"><i class="fas fa-cogs me-2"></i>Setting</a>
        <a href="#"><i class="fas fa-users me-2"></i>Pengguna</a>
        <a href="#"><i class="fas fa-box me-2"></i>Data Paket</a>
        <a href="#"><i class="fas fa-user me-2"></i>Data Pelanggan</a>
        <a href="#"><i class="fas fa-arrow-down me-2"></i>Kas Masuk</a>
        <a href="#"><i class="fas fa-arrow-up me-2"></i>Kas Keluar</a>
        <a href="#"><i class="fas fa-money-bill me-2"></i>Transaksi Pembayaran</a>
        <a href="#"><i class="fas fa-file-alt me-2"></i>Laporan Kas</a>
    </div>
    
    <!-- Navbar -->
    <nav class="navbar">
        <h4>Admin Dashboard</h4>
        <div>
            <span class="me-3">Admin</span>
            <a href="#" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>
    
    <!-- Content Area -->
    <div class="content">
        <h2>Dashboard</h2>
        <p>Selamat datang di halaman admin.</p>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 LILIK NET | All Rights Reserved
    </footer>
</body>
</html>
