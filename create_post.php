<?php
session_start();
require_once('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id']; // Logged-in user's ID

    // Insert post
    $stmt = $conn->prepare("INSERT INTO posts (title, content, created_at, user_id) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("ssi", $title, $content, $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- ✅ Include CSS -->
    
</head>
<body>
    <h2>Create a New Post</h2>
    <form method="POST">
        Title: <input type="text" name="title" required><br><br>
        Content:<br>
        <textarea name="content" rows="5" cols="40" required></textarea><br><br>
        <input type="submit" value="Publish">
    </form>
</body>
</html>
