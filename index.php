<?php
session_start();
include 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Count total posts
$countSql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
$totalResult = $conn->query($countSql)->fetch_assoc();
$totalPosts = $totalResult['total'];
$totalPages = ceil($totalPosts / $limit);

// Get posts with search & pagination
$sql = "SELECT * FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%' ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>📝 Blog App</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container mt-4">
  <h2 class="text-center">📝 Blog Posts</h2>

  <form class="d-flex mb-4" method="GET">
    <input class="form-control me-2" type="search" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-success" type="submit">Search</button>
  </form>

  <a href="create.php" class="btn btn-primary mb-3">+ Create New Post</a>

  <?php while($row = $result->fetch_assoc()): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
        <p class="card-text"><?= htmlspecialchars($row['content']) ?></p>
        <p><small class="text-muted">Posted on <?= $row['created_at'] ?></small></p>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?');">Delete</a>
      </div>
    </div>
  <?php endwhile; ?>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>
</body>
</html>
