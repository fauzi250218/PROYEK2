<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LILIK NET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #007bff, #ffffff);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: all 0.3s ease-in-out;
        }
        .login-container:hover {
            transform: scale(1.03);
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.2);
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #007bff;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 25px;
            transition: background 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .footer {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        .footer a {
            color: #007bff;
            margin: 0 10px;
            font-size: 24px;
            transition: transform 0.3s;
        }
        .footer a:hover {
            transform: scale(1.2);
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-container input {
            flex: 1;
            padding-right: 40px; /* Ruang untuk ikon */
        }
        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm w-100">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">LILIK.NET</a>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="container d-flex justify-content-center align-items-center flex-grow-1">
        <div class="login-container">
            <h2>Login</h2>
            <p class="text-muted">Solusi Internet Murah dan Cepat</p>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
    
            <form method="POST" action="{{ route('login_admin') }}">
                @csrf
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Nama Pengguna</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nama Pengguna" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom w-100 text-white">Masuk</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-3 w-100 text-center shadow-sm">
        <div class="container">
            <a href="#" class="text-primary me-3"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="#" class="text-primary me-3"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="text-primary"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });
    </script>
</body>
</html>