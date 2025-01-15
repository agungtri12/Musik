<?php 
include '../includes/db.php';

// Ambil detail album berdasarkan ID album dari URL
$album_id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($album_id == 0) {
    echo "ID album tidak valid.";
    exit;
}

// Ambil detail album
$album_query = $conn->prepare("SELECT * FROM albums WHERE id = :album_id LIMIT 1");
$album_query->execute(['album_id' => $album_id]);
$album = $album_query->fetch();

// Ambil lagu-lagu dalam album
$songs_query = $conn->prepare("SELECT * FROM songs WHERE album_id = :album_id");
$songs_query->execute(['album_id' => $album_id]);
$songs = $songs_query->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['name']); ?> - Album</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #121212; /* Latar belakang gelap */
            color: #fff;
            line-height: 1.6;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 48px;
            color: #FF4081;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Gaya Logo Navbar */
        .navbar .logo {
            font-size: 24px;
            font-weight: 700;
            color: #FF4081;
            display: flex;
            align-items: center;
        }

        .navbar .logo .logo-img {
            width: 40px; /* Sesuaikan ukuran logo */
            height: 40px;
            margin-right: 10px; /* Ruang antara logo dan teks */
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #333;
        }

        /* Detail Album */
        .album-detail {
            text-align: center;
            margin-bottom: 40px;
        }

        .album-detail img {
            width: 350px;
            height: 350px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .album-detail img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7);
        }

        .album-detail h2 {
            font-size: 36px;
            margin: 20px 0;
            color: #FF4081;
            font-weight: bold;
        }

        /* Daftar Lagu */
        .songs-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .song {
            display: flex;
            align-items: center;
            background-color: #2c2c2c;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
            width: 100%;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .song:hover {
            transform: translateX(10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7);
        }

        .song .song-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .song .number {
            font-size: 18px;
            color: #FF4081;
        }

        .song img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #FF4081;
        }

        .song .song-name {
            font-size: 22px;
            color: #fff;
            font-weight: 600;
        }

        .song .controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .song .controls i {
            font-size: 22px;
            color: #FF4081;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .song .controls i:hover {
            color: #e4407d;
        }

        /* Pemutar Audio */
        .audio-player {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        audio {
            width: 100%;
            max-width: 300px;
            background-color: #333;
            border-radius: 5px;
            color: #fff;
        }

        .play-pause-btn {
            font-size: 30px;
            color: #FF4081;
            margin-top: 10px;
            cursor: pointer;
        }

        /* Visualisasi Frekuensi */
        #frequencyVisualizer {
            margin-top: 10px;
            width: 100%;
            height: 100px;
            background-color: #111;
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
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #e53935;
        }

        .like-container {
            margin-top: 20px;
            text-align: center;
        }

        .like-btn {
            background-color: #FF4081;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .like-btn:hover {
            background-color: #e5397d;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="../assets/images/logo.png" alt="Logo Musik" class="logo-img">
        Pemutar Musik
    </div>
    <div>
        <a href="./index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Beranda</a>
        <a href="./albums.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'albums.php') ? 'active' : ''; ?>">Album</a>
        <a href="./artists.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'artists.php') ? 'active' : ''; ?>">Artis</a>
        <a href="../logout.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">Keluar</a>
    </div>
</div>

<h1><?php echo htmlspecialchars($album['name']); ?> - Album</h1>

<div class="album-detail">
    <img src="../uploads/albums/<?php echo htmlspecialchars($album['image']); ?>" alt="<?php echo htmlspecialchars($album['name']); ?>">
    <div class="like-container">
        <button id="like-btn" class="like-btn">
            ❤️ <span id="like-count"><?php echo $album['likes']; ?></span>
        </button>
    </div>
</div>

<div class="songs-list">
    <?php
    if (count($songs) > 0) {
        $counter = 1;
        foreach ($songs as $song) {
            $song_name = $song['title'] ?? 'Lagu Tidak Dikenal';
            $song_file = $song['file_path'] ?? '#'; 

            echo "
            <div class='song'>
                <div class='song-info'>
                    <div class='number'>{$counter}</div>
                    <img src='../uploads/images/{$song['image_path']}' alt='{$song_name}'>
                    <div class='song-name'>{$song_name}</div>
                </div>
                <div class='controls'>
                    <i class='play-pause-btn' id='playPause{$song['id']}'>▶️</i>
                </div>
                <audio id='audio{$song['id']}' src='../uploads/songs/{$song_file}' preload='none'></audio>
            </div>";
            $counter++;
        }
    } else {
        echo "<p>Tidak ada lagu dalam album ini.</p>";
    }
    ?>
</div>

<script>
    window.onload = function() {
        const playPauseButtons = document.querySelectorAll('.play-pause-btn');
        playPauseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const songId = this.id.replace('playPause', '');
                const audioElement = document.getElementById(`audio${songId}`);
                const isPlaying = !audioElement.paused;

                if (isPlaying) {
                    audioElement.pause();
                    this.innerHTML = '▶️'; // Tampilkan ikon putar
                } else {
                    audioElement.play();
                    this.innerHTML = '❚❚'; // Tampilkan ikon jeda
                }
            });
        });
    };

    document.getElementById('like-btn').addEventListener('click', function() {
        const likeBtn = this;
        const likeCount = document.getElementById('like-count');
        const albumId = <?php echo $album_id; ?>;

        fetch('../api/like.php?album_id=' + albumId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let count = parseInt(likeCount.innerText);
                    likeCount.innerText = count + 1;
                    likeBtn.disabled = true; // Disable tombol setelah memberi like
                }
            });
    });
</script>
</body>
</html>
