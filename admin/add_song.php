<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        /* Navbar */
        .navbar {
            width: 100%;
            background-color: #333;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar ul li {
            margin: 0 20px;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #4CAF50;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 80px; /* To avoid overlap with navbar */
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            button {
                font-size: 14px;
            }

            .navbar ul li {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <ul>
            <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="add_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_album.php') ? 'active' : ''; ?>">Add Album</a></li>
            <li><a href="add_song.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_song.php') ? 'active' : ''; ?>">Add Song</a></li>
            <li><a href="view_songs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_songs.php') ? 'active' : ''; ?>">View Songs</a></li>
        </ul>
    </div>

    <!-- Content -->
    <div class="container">
        <h1>Add Song</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Song Title:</label>
            <input type="text" name="title" required>

            <label for="artist">Artist:</label>
            <input type="text" name="artist" required>

            <label for="file">Audio File:</label>
            <input type="file" name="file" required>

            <label for="image">Album Cover Image:</label>
            <input type="file" name="image" required>

            <label for="album_id">Album:</label>
            <select name="album_id" required>
                <?php
                $albums = $conn->query("SELECT id, name FROM albums")->fetchAll();
                foreach ($albums as $album) {
                    echo "<option value='{$album['id']}'>{$album['name']}</option>";
                }
                ?>
            </select>

            <button type="submit" name="submit">Add Song</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $title = $_POST['title'];
            $artist = $_POST['artist'];
            $audioFile = $_FILES['file']['name'];
            $imageFile = $_FILES['image']['name'];
            $album_id = $_POST['album_id'];
            $targetAudio = "../uploads/songs/" . basename($audioFile);
            $targetImage = "../uploads/images/" . basename($imageFile);

            // Upload audio file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetAudio)) {
                // Upload image file
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetImage)) {
                    $sql = "INSERT INTO songs (title, artist, file_path, image_path, album_id) VALUES (:title, :artist, :file_path, :image_path, :album_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['title' => $title, 'artist' => $artist, 'file_path' => $audioFile, 'image_path' => $imageFile, 'album_id' => $album_id]);
                    echo "<p class='message success'>Song added successfully!</p>";
                } else {
                    echo "<p class='message error'>Failed to upload image file.</p>";
                }
            } else {
                echo "<p class='message error'>Failed to upload audio file.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
