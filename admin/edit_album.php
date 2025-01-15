<?php
include '../includes/db.php';

// Mendapatkan data album berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM albums WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$album) {
        echo "<p class='message error'>Album not found.</p>";
        exit;
    }
}

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $album['image']; // Gambar lama sebagai default

    // Proses unggah gambar jika ada
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/albums/";
        $image = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $image;

        // Validasi tipe file gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Hapus file gambar lama
                if ($album['image'] && file_exists($targetDir . $album['image'])) {
                    unlink($targetDir . $album['image']);
                }
            } else {
                echo "<p class='message error'>Failed to upload image!</p>";
            }
        } else {
            echo "<p class='message error'>Invalid image type!</p>";
        }
    }

    // Update data album
    $stmt = $conn->prepare("UPDATE albums SET name = :name, image = :image WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'image' => $image,
        'id' => $id
    ]);

    // Redirect ke halaman view_album.php
    header('Location: view_album.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
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

        .container {
            margin-top: 50px;
            background-color: #222;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #ff9a8b;
            margin-bottom: 20px;
            font-size: 32px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .cancel-btn {
            background-color: #f44336;
            margin-top: 10px;
        }

        .cancel-btn:hover {
            background-color: #e53935;
        }

        .current-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .current-image img {
            max-width: 100px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Album</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Album Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($album['name']); ?>" required>

            <div class="current-image">
                <p>Current Image:</p>
                <img src="../uploads/albums/<?php echo htmlspecialchars($album['image']); ?>" alt="<?php echo htmlspecialchars($album['name']); ?>">
            </div>

            <label for="image">New Image (Optional):</label>
            <input type="file" name="image" id="image">

            <button type="submit">Update Album</button>
            <a href="view_album.php" class="cancel-btn" style="display: inline-block; text-align: center; text-decoration: none;">Cancel</a>
        </form>
    </div>
</body>
</html>
