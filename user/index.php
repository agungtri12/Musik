<?php 
include '../includes/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemutar Musik</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Reset Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(135deg, #0d0d0d, #1c1c1c);
            color: #fff;
            line-height: 1.6;
            padding: 20px;
            overflow-x: hidden;
        }

        h1 {
            text-align: center;
            font-size: 3rem;
            color: #FF4081;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #111;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #FF4081;
        }

        .navbar ul {
            display: flex;
            list-style: none;
        }

        .navbar ul li {
            margin: 0 10px;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #ff4081;
            transform: scale(1.1);
        }

        /* Form Pencarian */
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        form input {
            padding: 12px;
            font-size: 1rem;
            border: 2px solid #FF4081;
            border-radius: 25px;
            width: 100%;
            max-width: 400px;
            margin-right: 10px;
            background-color: rgba(51, 51, 51, 0.8);
            color: #fff;
            backdrop-filter: blur(5px);
        }

        form button {
            padding: 12px 20px;
            background-color: #FF4081;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #e4407d;
        }

        /* Bagian Album */
        h2 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 40px;
            color: #FF4081;
            text-transform: uppercase;
        }

        .album-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .album {
            background-color: rgba(51, 51, 51, 0.7);
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .album img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .album h3 {
            margin: 15px 0;
            font-size: 1.5rem;
            color: #fff;
            font-weight: 700;
        }

        .album p {
            font-size: 0.9rem;
            color: #ddd;
            margin-bottom: 15px;
        }

        .album a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FF4081;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .album a:hover {
            background-color: #e4407d;
        }

        /* Efek Hover */
        .album:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .album img:hover {
            transform: scale(1.1);
        }

        /* Gaya Pemutar Audio */
        .audio-player-container {
            background-color: rgba(51, 51, 51, 0.7);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .audio-player-container img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .audio-player-container div {
            flex-grow: 1;
        }

        .audio-player-container h3 {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 5px;
        }

        .audio-player-container p {
            font-size: 0.9rem;
            color: #ddd;
        }

        .audio-player-container audio {
            width: 100%;
            margin-top: 10px;
            border-radius: 5px;
            background-color: rgba(51, 51, 51, 0.7);
        }

        /* Tombol Logout */
        .logout-btn {
            display: block;
            width: 250px;
            padding: 15px;
            margin: 40px auto;
            background-color: #f44336;
            color: white;
            text-align: center;
            border-radius: 25px;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #e53935;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .navbar ul li {
                margin: 5px 0;
            }

            .album img {
                height: 120px;
            }

            form input {
                width: 300px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
   <!-- Navbar -->
   <div class="navbar">
        <ul>
            <li><a href="../index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="add_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_album.php') ? 'active' : ''; ?>">Album</a></li>
            <li><a href="add_song.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_song.php') ? 'active' : ''; ?>">Artist</a></li>
            <li><a href="view_songs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_songs.php') ? 'active' : ''; ?>">lagu</a></li>
        </ul>
    </div>


    <h1>Selamat datang di Pemutar Musik</h1>

    <!-- Form Pencarian -->
    <form method="GET">
        <input type="text" name="query" placeholder="Cari album, artis, lagu" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        <button type="submit">Cari</button>
    </form>

    <?php
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $query = $_GET['query'];
        $sql = "SELECT * FROM songs WHERE title LIKE :query OR artist LIKE :query";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        $results = $stmt->fetchAll();

        // Check if results are found
        if ($stmt->rowCount() > 0) {
            // Display search results
            foreach ($results as $song) {
                echo "<div class='audio-player-container'>
                    <img src='../uploads/images/{$song['image_path']}' alt='{$song['title']}'>
                    <div>
                        <h3>{$song['title']} - {$song['artist']}</h3>
                        <p>{$song['id']}</p>
                        <audio controls>
                            <source src='../uploads/songs/{$song['file_path']}' type='audio/mp3'>
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>";
            }
        } else {
            // Display message when no results are found
            echo "<div>
                <h2>No results found for '<strong>{$query}</strong>'.</h2>
            </div>";
        }
    } else {
        // Display featured albums if no search query
        echo "<h2>Featured Albums</h2>";
        echo "<div class='album-list'>";
        $albums = $conn->query("SELECT a.*, COUNT(s.id) as total_songs FROM albums a LEFT JOIN songs s ON a.id = s.album_id GROUP BY a.id")->fetchAll();
        foreach ($albums as $album) {
            echo "
            <div class='album'>
                <img src='../uploads/albums/{$album['image']}' alt='{$album['name']}'>
                <h3>{$album['name']}</h3>
                <p>{$album['total_songs']} songs</p>
                <a href='album.php?id={$album['id']}'>View Album</a>
            </div>";
        }
        echo "</div>";
    }
    ?>
</body>
</html>
