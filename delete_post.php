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

// Only allow author to delete
if ($_SESSION['user_id'] != $post['user_id']) {
    die("You are not allowed to delete this post.");
}

// Delete post
$conn->query("DELETE FROM posts WHERE id=$id");
header("Location: index.php");
exit;
?>
