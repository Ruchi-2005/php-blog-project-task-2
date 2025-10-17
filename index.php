<?php
session_start();
require_once('db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog - Home</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Optional CSS -->
</head>
<body>

<h2>Welcome to the Blog!</h2>
<p>Hello, <?php echo htmlspecialchars($username); ?>! 
   <a href='create_post.php'>Create Post</a> | 
   <a href='logout.php'>Logout</a>
</p>
<hr>

<?php
// Fetch all posts
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

// Display posts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<article>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<p><strong>Posted by " . htmlspecialchars($row['username']) . " on " . $row['created_at'] . "</strong></p>";

        // Edit/Delete options for post owner
        if ($row['username'] == $_SESSION['username']) {
            echo "<a href='edit_post.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='delete_post.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
        }

        echo "</article><hr>";
    }
} else {
    echo "<p>No posts found.</p>";
}
?>

</body>
</html>

