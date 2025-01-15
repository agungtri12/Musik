<?php 
include '../includes/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemutar Musik</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Roboto:wght@300;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2a2a2a, #1e1e1e);
            color: #fff;
            line-height: 1.6;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 3rem;
            color: #FF4081;
            margin-bottom: 10px;
        }

        h1 span {
            color: #FFC107;
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
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #FF4081;
        }

        .navbar .logo i {
            margin-right: 10px;
        }

        .navbar ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
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
            background-color: #FF4081;
            transform: scale(1.1);
        }

        /* Search Form in Navbar */
        .navbar .search-form {
            display: flex;
            align-items: center;
        }

        .navbar .search-form input[type="text"] {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
            margin-right: 10px;
        }

        .navbar .search-form button {
            padding: 5px 15px;
            background-color: #FF4081;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar .search-form button:hover {
            background-color: #e4407d;
        }
        .navbar ul li a#logout-btn i {
    margin-right: 8px;
    font-size: 1.2rem;
    vertical-align: middle;
}


    .navbar ul li a#logout-btn:hover {
        background-color: #D32F2F;
    }

        /* Banner */
        .banner {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px;
            background: linear-gradient(135deg, #FF4081, #FFC107);
            border-radius: 15px;
        }

        .banner h2 {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 15px;
        }

        .banner p {
            font-size: 1.1rem;
            color: #333;
        }
        /* Pencarian */
        .audio-player-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background-color: rgba(51, 51, 51, 0.7);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    position: relative;
    transition: background-color 0.3s ease;
}

.audio-player-container:hover {
    background-color: rgba(51, 51, 51, 0.9);
}

.audio-player-container img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
}

.audio-player-container .song-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.audio-player-container .song-info h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #FF4081;
}

.audio-player-container .song-info p {
    margin: 5px 0;
    font-size: 1rem;
    color: #ddd;
}

.audio-player-container .pause-btn {
    position: absolute;
    top: 30px;
    right: 10px;
    background-color: #FF4081;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    cursor: pointer;
    transition: opacity 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
}

.audio-player-container:hover .pause-btn {
    opacity: 1;
}

.audio-player-container .pause-btn:hover {
    background-color: #e4407d;
    transform: scale(1.1);
}

.audio-player-container .pause-btn i {
    font-size: 1.2rem;
}

         /* Album List */
         .album-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    padding: 20px;
}

.album {
    position: relative;
    background-color: rgba(51, 51, 51, 0.7);
    border-radius: 15px;
    overflow: hidden;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.album img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.album .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    opacity: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease;
}

.album .overlay h3 {
    margin: 10px 0;
    font-size: 1.2rem;
    color: #FF4081;
}

.album .overlay p {
    font-size: 1rem;
    margin: 5px 0;
    color: #ddd;
}

.album .overlay .pause-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    background-color: #FF4081;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.album .overlay .pause-btn:hover {
    background-color: #e4407d;
    transform: scale(1.1);
}

.album .overlay .pause-btn i {
    font-size: 1.2rem;
}


.album:hover img {
    transform: scale(1.1);
}

.album:hover .overlay {
    opacity: 1;
}

        /* Responsiveness */
        @media (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
            }

            .navbar .search-form {
                margin-top: 10px;
                flex-direction: column;
            }

            .navbar .search-form input[type="text"] {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo"><i class="fas fa-music"></i> Pemutar Musik</div>
    <ul>
        <li><a href="../index.php" class="active">Home</a></li>
        <li><a href="albums.php">Album</a></li>
        <li><a href="artis.php">Artist</a></li>
        <li><a href="view_songs.php">Lagu</a></li>
        <li><a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> </a></li>
    </ul>
    <form method="GET" class="search-form">
        <input type="text" name="query" placeholder="Cari album, artis, lagu" 
               value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        <button type="submit">Cari</button>
    </form>
</div>


    <div class="banner">
        <h2>Temukan Musik Favoritmu</h2>
        <p>Dengarkan lagu terbaik dari berbagai artis dan genre.</p>
    </div>

    <?php
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $query = $_GET['query'];
        $sql = "SELECT s.*, a.name as album_name, a.id as album_id FROM songs s 
                LEFT JOIN albums a ON s.album_id = a.id 
                WHERE s.title LIKE :query OR s.artist LIKE :query";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        $results = $stmt->fetchAll();
    
        if ($stmt->rowCount() > 0) {
            foreach ($results as $song) {
                echo "
                <div class='audio-player-container'>
                    <img src='../uploads/images/{$song['image_path']}' alt='{$song['title']}'>
                    <div class='song-info'>
                        <h3>{$song['title']}</h3>
                        <p>{$song['artist']}</p>
                    </div>
                    <button class='pause-btn' onclick=\"window.location.href='album.php?id={$song['album_id']}'\">
                        <i class='fas fa-play'></i>
                    </button>
                </div>";
            }
        } else {
            echo "<div>
                <h2>No results found for '<strong>{$query}</strong>'.</h2>
            </div>";
        }
} else {
    echo "<h2>Featured Albums</h2>";
    echo "<div class='album-list'>";
$albums = $conn->query("SELECT a.*, COUNT(s.id) as total_songs FROM albums a LEFT JOIN songs s ON a.id = s.album_id GROUP BY a.id")->fetchAll();
foreach ($albums as $album) {
    echo "
    <div class='album'>
        <a href='album.php?id={$album['id']}' style='text-decoration: none; color: inherit;'>
            <img src='../uploads/albums/{$album['image']}' alt='{$album['name']}'>
            <div class='overlay'>
                <h3>{$album['name']}</h3>
                <p>{$album['total_songs']} songs</p>
                <button class='pause-btn'>
                    <i class='fas fa-play'></i>
                </button>
            </div>
        </a>
    </div>";
}
echo "</div>";

}
?>
<!-- Confirmation Modal for Logout -->
<div id="logout-confirmation" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#111; color:white; padding:20px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.5);">
    <h3>Yakin ingin keluar?</h3>
    <button onclick="confirmLogout(true)" style="background:#FF4081; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; margin-right:10px;">Ya</button>
    <button onclick="confirmLogout(false)" style="background:#D32F2F; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Tidak</button>
</div>

<script>
    const logoutButton = document.getElementById('logout-btn');
    const logoutConfirmation = document.getElementById('logout-confirmation');

    logoutButton.addEventListener('click', (e) => {
        e.preventDefault();
        logoutConfirmation.style.display = 'block';
    });

    function confirmLogout(confirm) {
        if (confirm) {
            window.location.href = "../logout.php";
        } else {
            logoutConfirmation.style.display = 'none';
        }
    }
</script>

</body>
</html>
