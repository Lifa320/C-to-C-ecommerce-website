<?php


$query = "SELECT * FROM categories ORDER BY category_id DESC";
$result = mysqli_query($con, $query);
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">All Categories</h2>
  <table class="table table-bordered table-striped text-center">
    <thead class="table-info">
      <tr>
        <th>Category ID</th>
        <th>Category Name</th>
       
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['category_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No categories found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
