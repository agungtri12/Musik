<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search</title>
</head>
<body>
<h1>Search</h1>
<form method="GET">
    <input type="text" name="query" placeholder="Search albums, artists, songs" required>
    <button type="submit">Search</button>
</form>
<?php
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM songs WHERE title LIKE :query OR artist LIKE :query";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    $results = $stmt->fetchAll();
    foreach ($results as $song) {
        echo "<div>
            <h2>{$song['title']} - {$song['artist']}</h2>
        </div>";
    }
}
?>
</body>
</html>
