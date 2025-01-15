<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Arahkan ke halaman user jika bukan admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Vibrant background */
            color: #fff;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .navbar {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Dark semi-transparent background */
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: center;
        }

        .navbar ul li {
            margin: 0 25px;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #ff4081; /* Highlight color */
            transform: scale(1.1); /* Slightly enlarge the button */
        }

        .container {
            margin-top: 80px; /* Space for fixed navbar */
            text-align: center;
        }

        h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 30px;
            margin-top: 50px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .card p {
            font-size: 18px;
            color: #fff;
            margin-bottom: 30px;
        }

        .card a {
            text-decoration: none;
            color: #fff;
            background-color: #ff4081;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .card a:hover {
            background-color: #ff33a1;
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            color: #fff;
            margin-top: 50px;
            font-size: 16px;
            font-weight: 600;
        }

        footer a {
            color: #ff4081;
            text-decoration: none;
        }

        footer a:hover {
            color: #ff33a1;
        }

    </style>
    <script>
        // Fungsi untuk mengonfirmasi apakah pengguna yakin ingin logout
        function confirmLogout(event) {
            event.preventDefault(); // Mencegah link untuk melakukan redirect langsung
            let confirmAction = confirm("Apakah Anda yakin ingin keluar?");
            if (confirmAction) {
                window.location.href = '../logout.php'; // Jika ya, redirect ke logout.php
            }
        }
    </script>
</head>
<body>

<div class="navbar">
        <ul>
            <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="add_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_album.php') ? 'active' : ''; ?>">Add Album</a></li>
            <li><a href="add_song.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_song.php') ? 'active' : ''; ?>">Add Song</a></li>
            <li><a href="view_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_album.php') ? 'active' : ''; ?>">View Album</a></li>
            <li><a href="view_songs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_songs.php') ? 'active' : ''; ?>">View Songs</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Welcome to the Admin Panel</h1>
        <div class="card">
            <p>Manage your music collection here with ease. Add albums, songs, and view all content.</p>
            <!-- Logout Button -->
            <a href="#" onclick="confirmLogout(event)">Logout</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Admin Panel - <a href="https://www.yoursite.com" target="_blank">Your Music Site</a></p>
    </footer>

</body>
</html>
