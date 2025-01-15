<?php
session_start();
include 'includes/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data pengguna berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Cek apakah pengguna ditemukan dan kata sandi cocok
    if ($user && password_verify($password, $user['password'])) {
        // Simpan data session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Menyimpan peran pengguna

        // Cek peran pengguna dan arahkan sesuai dengan itu
        if ($user['role'] == 'admin') {
            // Arahkan ke halaman admin jika peran admin
            header("Location: admin/index.php");
            exit();
        } else {
            // Arahkan ke halaman user jika peran user
            header("Location: user/index.php");
            exit();
        }
    } else {
        // Jika login gagal
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Music Theme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1db954, #191414);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: #1db954;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 20%;
        }

        .form-group input {
            width: 70%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
            background-color: #333;
            color: #fff;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #1db954;
            outline: none;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 125px;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1db954;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1ed760;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #1db954;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .vinyl-record {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, #000 60%, transparent 61%), #fff;
            border-radius: 50%;
            top: 90px;
            right: -10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            color: #1DB954;
            text-shadow: 1px 1px 2px #000;
            animation: spin 6s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .vinyl-record span {
            z-index: 1;
        }
    </style>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Login Musik</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <span id="toggle-password" class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
            </div>

            <button type="submit" name="login">Masuk</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>

    <div class="vinyl-record">
        <span>AGM</span>
    </div>

</body>
</html>
