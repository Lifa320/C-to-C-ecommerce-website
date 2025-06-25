<?php
include('../includes/connect.php');


// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch customers and total spent
$query = "
    SELECT 
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        u.user_phone,
        u.created_at,
        IFNULL(SUM(o.total_amount), 0) AS total_spent
    FROM users u
    LEFT JOIN orders o ON u.user_id = o.user_id
    WHERE u.role = 'customer'
    GROUP BY u.user_id
    ORDER BY u.created_at DESC
";

$result = mysqli_query($con, $query);
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">All Registered Customers</h2>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-info">
      <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Registered At</th>
        <th>Total Spent (R)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['user_phone'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>R <?= number_format($row['total_spent'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-muted">No customers found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>