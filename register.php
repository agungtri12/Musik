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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - User</title>
</head>
<body>

    <h1>Register to Create an Account</h1>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>
