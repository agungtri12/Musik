<?php include '../includes/db.php'; ?>

<?php
// Check if a song needs to be deleted
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM songs WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $delete_id]);

    echo "<p class='message success'>Song deleted successfully!</p>";
}

// Fetch all songs
$sql = "SELECT * FROM songs";
$stmt = $conn->query($sql);
$songs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Songs</title>
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
            background-color: rgba(51, 51, 51, 0.8);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin-top: 100px; /* To avoid overlap with navbar */
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #4CAF50;
            text-decoration: none;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background-color: #e0f7e0;
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

            .navbar ul li {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <ul>
            <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="add_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_album.php') ? 'active' : ''; ?>">Add Album</a></li>
            <li><a href="add_song.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add_song.php') ? 'active' : ''; ?>">Add Song</a></li>
            <li><a href="view_songs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_songs.php') ? 'active' : ''; ?>">View Songs</a></li>
        </ul>
    </div>

    <div class="container">
        <h1>View Songs</h1>

        <table>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($songs as $song) { ?>
                <tr>
                    <td><?php echo $song['title']; ?></td>
                    <td><?php echo $song['artist']; ?></td>
                    <td>
                        <?php
                        $album_id = $song['album_id'];
                        $album = $conn->query("SELECT name FROM albums WHERE id = $album_id")->fetch();
                        echo $album['name'];
                        ?>
                    </td>
                    <td>
                        <a href="edit_song.php?id=<?php echo $song['id']; ?>">Edit</a> |
                        <a href="view_songs.php?delete_id=<?php echo $song['id']; ?>" onclick="return confirm('Are you sure you want to delete this song?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
