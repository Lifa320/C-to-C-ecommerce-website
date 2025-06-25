<?php
include('../includes/connect.php');

// Handle search filter
$search_query = "";
$search_sql = "";

if (isset($_GET['search'])) {
  $keyword = mysqli_real_escape_string($con, $_GET['keyword']);
  $category = mysqli_real_escape_string($con, $_GET['category']);
  $seller = mysqli_real_escape_string($con, $_GET['seller']);

  $conditions = [];
  if (!empty($keyword)) {
    $conditions[] = "p.keyword LIKE '%$keyword%'";
  }
  if (!empty($category)) {
    $conditions[] = "c.name LIKE '%$category%'";
  }
  if (!empty($seller)) {
    $conditions[] = "u.first_name LIKE '%$seller%'";
  }

  if (!empty($conditions)) {
    $search_sql = "WHERE " . implode(" AND ", $conditions);
  }
}

// Fetch products with joins
$query = "
  SELECT 
    p.product_id, p.name AS product_name, p.image, p.price, p.stock_quantity, 
    p.keyword, p.created_at, 
    c.name AS category_name, 
    u.first_name AS seller_name
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  LEFT JOIN users u ON p.user_id = u.user_id
  $search_sql
  ORDER BY p.product_id DESC
";

$result = mysqli_query($con, $query);
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">All Products</h2>

  <!-- Search Filter -->
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
      <input type="text" name="keyword" class="form-control" placeholder="Search by keyword" value="<?= $_GET['keyword'] ?? '' ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="category" class="form-control" placeholder="Search by category" value="<?= $_GET['category'] ?? '' ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="seller" class="form-control" placeholder="Search by seller name" value="<?= $_GET['seller'] ?? '' ?>">
    </div>
    <div class="col-md-3">
      <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <!-- Product Table -->
  <table class="table table-bordered table-striped text-center">
    <thead class="table-info">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th>
        <th>Price (R)</th>
        <th>Stock</th>
        <th>Keyword</th>
        <th>Category</th>
        <th>Seller</th>
        <th>Created At</th>
      
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['product_id']) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>
  <img src="../seller_area/product_images/<?= htmlspecialchars($row['image']) ?>" 
       alt="<?= htmlspecialchars($row['product_name']) ?>" 
       style="width: 60px; height: 60px; object-fit: cover;">
            </td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
            <td><?= htmlspecialchars($row['keyword']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
            <td><?= htmlspecialchars($row['seller_name'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="10">No products found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
