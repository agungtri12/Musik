<?php
session_start();
include 'includes/db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi untuk memastikan kata sandi cocok
    if ($password !== $confirm_password) {
        $error = "Kata sandi tidak cocok!";
    } else {
        // Mengecek apakah username sudah terdaftar
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Enkripsi kata sandi
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Menyimpan data pengguna baru ke database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')");
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);

            // Arahkan pengguna ke halaman login setelah registrasi sukses
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Website Musik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #1DB954, #191414);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        .container {
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2.5rem;
        }
        form {
            background: #292929;
            padding: 30px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        form label {
            width: 100%;
            text-align: left;
            margin-bottom: 5px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        form button {
            background-color: #1DB954;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }
        form button:hover {
            background-color: #17a445;
        }
        a {
            color: #1DB954;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .corner-record {
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
            animation: spin 6s linear infinite; /* Menambahkan animasi */
        }
        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
        .record::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: #1DB954;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="corner-record">AGM</div>
    <div class="container">
        <h1>Daftar untuk Mendengarkan Musik</h1>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Masukkan username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Masukkan email" required>

            <label for="password">Kata Sandi:</label>
            <input type="password" name="password" placeholder="Masukkan kata sandi" required>

            <label for="confirm_password">Konfirmasi Kata Sandi:</label>
            <input type="password" name="confirm_password" placeholder="Konfirmasi kata sandi" required>

            <button type="submit" name="register">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </div>
</body>
</html>
