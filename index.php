<?php
session_start();
require_once('db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Pagination setup
$posts_per_page = 3; // number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $posts_per_page;

// Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog - Home</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- ✅ Include CSS -->
</head>
<body>

<h2>Welcome to the Blog!</h2>
<p>Hello, <?php echo htmlspecialchars($username); ?>! 
   <a href='create_post.php'>Create Post</a> | 
   <a href='logout.php'>Logout</a>
</p>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>
<hr>

<?php
// Build SQL query
if (!empty($search)) {
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.title LIKE ? OR posts.content LIKE ?
            ORDER BY posts.created_at DESC
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $like_search = "%" . $search . "%";
    $stmt->bind_param("ssii", $like_search, $like_search, $start_from, $posts_per_page);
} else {
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $start_from, $posts_per_page);
}

$stmt->execute();
$result = $stmt->get_result();

// Display posts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<article>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<p><strong>Posted by " . htmlspecialchars($row['username']) . " on " . $row['created_at'] . "</strong></p>";

        if ($row['username'] == $_SESSION['username']) {
            echo "<a href='edit_post.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='delete_post.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
        }
        echo "</article><hr>";
    }
} else {
    echo "<p>No posts found.</p>";
}

// Pagination links
if (!empty($search)) {
    $count_sql = "SELECT COUNT(*) AS total FROM posts WHERE title LIKE ? OR content LIKE ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("ss", $like_search, $like_search);
} else {
    $count_sql = "SELECT COUNT(*) AS total FROM posts";
    $count_stmt = $conn->prepare($count_sql);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$row = $count_result->fetch_assoc();
$total_posts = $row['total'];
$total_pages = ceil($total_posts / $posts_per_page);

echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<strong>$i</strong> ";
    } else {
        $search_param = !empty($search) ? "&search=" . urlencode($search) : "";
        echo "<a href='index.php?page=$i$search_param'>$i</a> ";
    }
}
echo "</div>";
?>

</body>
</html>
