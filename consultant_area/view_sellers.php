<?php
include('../includes/connect.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Join sellers, business_requests, and users tables
$query = "
    SELECT 
        s.seller_id, 
        s.user_id,
        u.email, 
        br.business_name, 
        br.telephone 
    FROM sellers s
    INNER JOIN users u ON s.user_id = u.user_id
    INNER JOIN business_requests br ON s.user_id = br.user_id
    ORDER BY s.seller_id DESC
";
$result = mysqli_query($con, $query);
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">All Registered Sellers</h2>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-info">
      <tr>
        <th>Seller ID</th>
       <th>User ID</th>
        <th>Business Name</th>
        <th>Telephone</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['seller_id']) ?></td>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['business_name']) ?></td>
            <td><?= htmlspecialchars($row['telephone']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
              <a href="index.php?view_seller_products=<?= $row['user_id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-boxes"></i> View Products
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5">No sellers found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
