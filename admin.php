<?php
include 'includes/db.php'; // Pastikan Anda sudah membuat koneksi ke database

// Data untuk akun admin
$username = 'admin1';
$password = 'admin1'; // Password yang akan di-hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Menggunakan password_hash untuk mengamankan password
$email = 'admin1@example.com';
$role = 'admin';

// Menyimpan data akun admin ke dalam tabel 'users'
try {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (:username, :password, :role, :email)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword,
        'role' => $role,
        'email' => $email
    ]);

    echo "Akun admin berhasil dibuat!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
