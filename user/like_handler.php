<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_id = isset($_POST['album_id']) ? intval($_POST['album_id']) : 0;

    if ($album_id > 0) {
        // Fetch current likes
        $query = $conn->prepare("SELECT likes FROM albums WHERE id = :album_id LIMIT 1");
        $query->execute(['album_id' => $album_id]);
        $album = $query->fetch();

        if ($album) {
            // Toggle likes
            $current_likes = intval($album['likes']);
            $action = isset($_POST['action']) && $_POST['action'] === 'like' ? 1 : -1;
            $new_likes = max(0, $current_likes + $action);

            // Update database
            $update_query = $conn->prepare("UPDATE albums SET likes = :likes WHERE id = :album_id");
            $update_query->execute(['likes' => $new_likes, 'album_id' => $album_id]);

            echo json_encode(['success' => true, 'likes' => $new_likes]);
            exit;
        }
    }
}

echo json_encode(['success' => false]);
exit;
?>
