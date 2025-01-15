<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Albums</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f06, #ff9a8b);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            height: 100vh;
            color: #fff;
        }

         /* Navbar Styling */
         .navbar {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #ff9a8b;
        }

        .container {
            margin-top: 100px;
            background-color: #222;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #ff9a8b;
            margin-bottom: 40px;
            font-size: 32px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #fff;
        }

        table th {
            background-color: #333;
        }

        table tr:hover {
            background-color: rgba(255, 154, 139, 0.3);
        }

        .action-btn {
            display: inline-block;
            padding: 8px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .delete-btn:hover {
            background-color: #e53935;
        }

        .message {
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            color: #33d1a2;
        }

        .message.error {
            color: #f44336;
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
            <li><a href="view_album.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_album.php') ? 'active' : ''; ?>">View Album</a></li>
            <li><a href="view_songs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'view_songs.php') ? 'active' : ''; ?>">View Songs</a></li>
        </ul>
    </div>

    <!-- Container -->
    <div class="container">
        <h1>View Albums</h1>

        <?php
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $stmt = $conn->prepare("DELETE FROM albums WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "<p class='message success'>Album deleted successfully!</p>";
        }

        $sql = "SELECT * FROM albums";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($albums) > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($albums as $album) {
                echo "<tr>
                        <td>{$album['id']}</td>
                        <td>{$album['name']}</td>
                        <td><img src='../uploads/albums/{$album['image']}' alt='{$album['name']}' width='80'></td>
                        <td>
                            <a href='edit_album.php?id={$album['id']}' class='action-btn edit-btn'>Edit</a>
                            <a href='view_album.php?delete={$album['id']}' class='action-btn delete-btn' onclick='return confirm(\"Apakah anda yakin ingin menghapus album?\");'>Delete</a>
                        </td>
                    </tr>";
            }

            echo "</tbody>
                </table>";
        } else {
            echo "<p class='message error'>No albums found.</p>";
        }
        ?>
    </div>

</body>
</html>
