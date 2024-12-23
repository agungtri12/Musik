<?php include '../includes/db.php'; ?>

<?php
include '../includes/db.php';

// Check if song ID is passed
if (!isset($_GET['id'])) {
    echo "Song not found!";
    exit;
}

$song_id = $_GET['id'];

// Fetch current song details
$sql = "SELECT * FROM songs WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $song_id]);
$song = $stmt->fetch();

if (!$song) {
    echo "Song not found!";
    exit;
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $audioFile = $_FILES['file']['name'];
    $imageFile = $_FILES['image']['name'];
    $album_id = $_POST['album_id'];

    $targetAudio = "../uploads/songs/" . basename($audioFile);
    $targetImage = "../uploads/images/" . basename($imageFile);

    // Start building the SQL query
    $sql = "UPDATE songs SET title = :title, artist = :artist, album_id = :album_id";

    // Add file_path if audio file is provided
    if ($audioFile) {
        move_uploaded_file($_FILES['file']['tmp_name'], $targetAudio);
        $sql .= ", file_path = :file_path";
    }

    // Add image_path if image file is provided
    if ($imageFile) {
        move_uploaded_file($_FILES['image']['tmp_name'], $targetImage);
        $sql .= ", image_path = :image_path";
    }

    $sql .= " WHERE id = :id";

    // Prepare and execute the SQL query dynamically
    $params = [
        'title' => $title,
        'artist' => $artist,
        'album_id' => $album_id,
        'id' => $song_id
    ];

    // Add additional parameters if files are provided
    if ($audioFile) {
        $params['file_path'] = $audioFile;
    }

    if ($imageFile) {
        $params['image_path'] = $imageFile;
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Redirect to view_songs.php after successful update
    header('Location: view_songs.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Song</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #101010; /* Dark background */
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
            margin: 50px auto;
            padding: 30px;
            background: linear-gradient(145deg, #333, #555);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #fff;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #fff;
        }

        input[type="text"], select, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            background-color: #222;
            color: #fff;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 8px;
            background-color: #444;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0077ff;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #005bb5;
        }

        .current-image {
            width: 120px;
            height: auto;
            margin-top: 10px;
            border-radius: 6px;
        }

        /* Image Preview */
        .preview-image {
            margin-top: 20px;
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            display: none;
        }

        /* Hover Effects */
        input[type="text"]:focus, select:focus, input[type="file"]:focus {
            border: 1px solid #0077ff;
            background-color: #333;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Song</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Song Title:</label>
            <input type="text" name="title" value="<?php echo $song['title']; ?>" required>

            <label for="artist">Artist:</label>
            <input type="text" name="artist" value="<?php echo $song['artist']; ?>" required>

            <label for="file">Audio File:</label>
            <input type="file" name="file" onchange="previewFile()">

            <label for="image">Album Cover Image:</label>
            <input type="file" name="image" onchange="previewImage()">

            <?php if ($song['image_path']) { ?>
                <label>Current Album Cover:</label>
                <img src="../uploads/images/<?php echo $song['image_path']; ?>" class="current-image" alt="Current Album Cover">
            <?php } ?>

            <label for="album_id">Album:</label>
            <select name="album_id" required>
                <?php
                $albums = $conn->query("SELECT id, name FROM albums")->fetchAll();
                foreach ($albums as $album) {
                    $selected = $album['id'] == $song['album_id'] ? 'selected' : '';
                    echo "<option value='{$album['id']}' $selected>{$album['name']}</option>";
                }
                ?>
            </select>

            <button type="submit" name="submit">Update Song</button>
        </form>
    </div>

    <script>
        // Preview image before uploading
        function previewImage() {
            var file = document.querySelector('input[type="file"][name="image"]').files[0];
            var preview = document.querySelector('.preview-image');
            var reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Preview audio file (optional)
        function previewFile() {
            var file = document.querySelector('input[type="file"][name="file"]').files[0];
            if (file) {
                var reader = new FileReader();
                reader.onloadend = function () {
                    console.log("Audio file ready for preview (optional):", reader.result);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
