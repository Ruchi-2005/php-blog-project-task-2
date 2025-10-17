<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must <a href='login.php'>login</a> first.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (title, content, user_id) VALUES ('$title', '$content', '$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Post created successfully! <a href='index.php'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Create New Post</h2>
<form method="POST">
    Title: <input type="text" name="title" required><br><br>
    Content:<br>
    <textarea name="content" rows="5" cols="40" required></textarea><br><br>
    <input type="submit" value="Post">
</form>
