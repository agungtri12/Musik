<?php 
include '../includes/db.php';

// Fetch album details based on album ID from the URL
$album_id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($album_id == 0) {
    echo "Invalid album ID.";
    exit;
}

// Fetch the album details
$album_query = $conn->prepare("SELECT * FROM albums WHERE id = :album_id LIMIT 1");
$album_query->execute(['album_id' => $album_id]);
$album = $album_query->fetch();

// Fetch the songs in the album
$songs_query = $conn->prepare("SELECT * FROM songs WHERE album_id = :album_id");
$songs_query->execute(['album_id' => $album_id]);
$songs = $songs_query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['name']); ?> - Album</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #0a0a0a, #1c1c1c);
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
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #111;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: 700;
            color: #FF4081;
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

        /* Album Details */
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
        }

        .album-detail h2 {
            font-size: 36px;
            margin: 20px 0;
            color: #FF4081;
            font-weight: bold;
        }

        /* Songs List */
        .songs-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            justify-items: center;
            padding: 0 10px;
        }

        .song {
            background-color: #333;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
            text-align: center;
            padding: 20px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .song h3 {
            margin: 15px 0;
            font-size: 20px;
            color: #fff;
            font-weight: 600;
            text-transform: capitalize;
        }

        .song a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FF4081;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .song a:hover {
            background-color: #e4407d;
        }

        .song:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Audio Player */
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

        /* Frequency Visualizer Canvas */
        #frequencyVisualizer {
            margin-top: 10px;
            width: 100%;
            height: 100px;
            background-color: #111;
        }

        /* Logout Button */
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Music Player</div>
        <div>
            <a href="./index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
            <a href="./albums.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'albums.php') ? 'active' : ''; ?>">Albums</a>
            <a href="./artists.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'artists.php') ? 'active' : ''; ?>">Artists</a>
            <a href="../logout.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">Logout</a>
        </div>
    </div>

    <h1><?php echo htmlspecialchars($album['name']); ?> - Album</h1>

    <!-- Album Details -->
    <div class="album-detail">
        <img src="../uploads/albums/<?php echo htmlspecialchars($album['image']); ?>" alt="<?php echo htmlspecialchars($album['name']); ?>">
        <h2><?php echo htmlspecialchars($album['name']); ?></h2>
    </div>

    <!-- Songs List -->
    <div class="songs-list">
        <?php
        if (count($songs) > 0) {
            foreach ($songs as $song) {
                // Use null coalescing operator for safe access
                $song_name = $song['title'] ?? 'Unknown Song';
                $song_file = $song['file_path'] ?? '#'; // Fallback to '#' if no file

                echo "
                <div class='song'>
                    <h3>{$song_name}</h3>
                    <div class='audio-player'>
                        <audio controls>
                            <source src='../uploads/songs/{$song_file}' type='audio/mpeg'>
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    <canvas id='frequencyVisualizer'></canvas>
                    <a href='../uploads/songs/{$song_file}' download>Download</a>
                </div>";
            }
        } else {
            echo "<p>No songs available in this album.</p>";
        }
        ?>
    </div>

    <!-- Logout Button -->
    <a href="../logout.php" class="logout-btn">Logout</a>

    <script>
        window.onload = function() {
            const audioElement = document.querySelector('audio');
            const canvas = document.getElementById('frequencyVisualizer');
            const ctx = canvas.getContext('2d');

            // Set up the audio context and analyser
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const analyser = audioContext.createAnalyser();
            const source = audioContext.createMediaElementSource(audioElement);

            // Connect the source to the analyser and to the output
            source.connect(analyser);
            analyser.connect(audioContext.destination);

            analyser.fftSize = 256; // Number of frequency bins
            const bufferLength = analyser.frequencyBinCount; // Number of data points

            // Create an array to store frequency data
            const dataArray = new Uint8Array(bufferLength);

            // Function to animate the visualizer
            function draw() {
                // Get the frequency data from the analyser
                analyser.getByteFrequencyData(dataArray);

                // Clear the canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Calculate the width of each bar
                const barWidth = canvas.width / bufferLength;

                // Loop through the frequency data and draw each bar
                for (let i = 0; i < bufferLength; i++) {
                    const barHeight = dataArray[i];
                    const red = barHeight + 100 * (i / bufferLength);
                    const green = 250 * (i / bufferLength);
                    const blue = 50;

                    ctx.fillStyle = `rgb(${red}, ${green}, ${blue})`;
                    ctx.fillRect(i * barWidth, canvas.height - barHeight, barWidth, barHeight);
                }

                // Call the draw function recursively to animate
                requestAnimationFrame(draw);
            }

            // Start the animation once the audio is ready
            audioElement.onplay = function() {
                audioContext.resume().then(() => {
                    draw();
                });
            };
        };
    </script>
</body>
</html>
