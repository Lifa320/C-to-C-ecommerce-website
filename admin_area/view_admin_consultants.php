<?php
include('../includes/connect.php');


// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
// Fetch consultant admins
$query = "
    SELECT 
        user_id,
        first_name,
        last_name,
        email,
        user_phone,
        created_at
    FROM users
    WHERE role = 'consultant_admin'
    ORDER BY created_at DESC
";

$result = mysqli_query($con, $query);
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">All Consultant Admins</h2>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-info">
      <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Registered At</th>
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
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-muted">No consultant admins found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>