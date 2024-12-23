<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Resetting margins and padding */
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

        /* Main Form Styling */
        .container {
            background-color: #222;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 650px;
            margin-top: 100px; /* Prevent overlap with navbar */
        }

        h1 {
            text-align: center;
            color: #ff9a8b;
            margin-bottom: 40px;
            font-size: 32px;
            font-weight: bold;
        }

        label {
            font-weight: 600;
            color: #fff;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #ff9a8b;
            outline: none;
        }

        button {
            background-color: #ff9a8b;
            color: #fff;
            padding: 16px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #f06;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            color: #33d1a2;
        }

        .message.error {
            color: #f44336;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .container {
                padding: 25px;
            }

            h1 {
                font-size: 28px;
            }

            button {
                font-size: 16px;
            }

            .navbar ul li {
                margin: 10px 0;
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

    <!-- Form for adding album -->
    <div class="container">
        <h1>Add Album</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Album Name:</label>
            <input type="text" name="name" required>

            <label for="image">Album Image:</label>
            <input type="file" name="image" required>

            <button type="submit" name="submit">Add Album</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $image = $_FILES['image']['name'];
            $target = "../uploads/albums/" . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $sql = "INSERT INTO albums (name, image) VALUES (:name, :image)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['name' => $name, 'image' => $image]);
                echo "<p class='message success'>Album added successfully!</p>";
            } else {
                echo "<p class='message error'>Failed to upload image.</p>";
            }
        }
        ?>
    </div>

</body>
</html>
