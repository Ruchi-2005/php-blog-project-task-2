<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$id = $_GET['id'];
// Fetch post
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();

// Only allow author to edit
if ($_SESSION['user_id'] != $post['user_id']) {
    die("You are not allowed to edit this post.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $conn->query("UPDATE posts SET title='$title', content='$content' WHERE id=$id");
    header("Location: index.php");
}
?>

<h2>Edit Post</h2>
<form method="POST">
    Title: <input type="text" name="title" value="<?= $post['title'] ?>"><br><br>
    Content:<br>
    <textarea name="content" rows="5" cols="40"><?= $post['content'] ?></textarea><br><br>
    <input type="submit" value="Update">
</form>
