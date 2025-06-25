<?php
include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("..users_area/user_login.php");
    exit();
}
?>
<link rel="stylesheet" href="../style.css">

<div class="justify-content:center p-4">
  <h2>Product Analytics Dashboard</h2>

  <ul class="nav nav-tabs" id="analyticsTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab">Highest Paying Customers</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="sellers-tab" data-bs-toggle="tab" data-bs-target="#sellers" type="button" role="tab">Highest Revenue Earned</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Most Selling Products</button>
    </li>
  </ul>

  <div class="tab-content mt-3" id="analyticsTabContent">

    <!-- Highest Paying Customers -->
    <div class="tab-pane fade show active" id="customers" role="tabpanel">
      <h5>Customers Who Spent More Than R10,000</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Spent (R)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.email, SUM(o.total_amount) AS total_spent
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                GROUP BY u.user_id
                HAVING total_spent > 10000
                ORDER BY total_spent DESC";
          $res = mysqli_query($con, $q);
          while ($row = mysqli_fetch_assoc($res)):
          ?>
            <tr>
              <td><?= $row['user_id'] ?></td>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td>R <?= number_format($row['total_spent'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Highest Revenue Sellers -->
    <div class="tab-pane fade" id="sellers" role="tabpanel">
      <h5>Sellers Who Earned More Than R40,000</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>Seller ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Revenue (R)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.email, SUM(oi.quantity * oi.price) AS total_revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                JOIN users u ON p.user_id = u.user_id
                GROUP BY p.user_id
                HAVING total_revenue > 40000
                ORDER BY total_revenue DESC";
          $res = mysqli_query($con, $q);
          while ($row = mysqli_fetch_assoc($res)):
          ?>
            <tr>
              <td><?= $row['user_id'] ?></td>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td>R <?= number_format($row['total_revenue'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Most Selling Products -->
    <div class="tab-pane fade" id="products" role="tabpanel">
      <h5>Most Selling Products</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Total Quantity Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT p.product_id, p.name, SUM(oi.quantity) AS total_sold
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                GROUP BY p.product_id
                ORDER BY total_sold DESC";
          $res = mysqli_query($con, $q);
          while ($row = mysqli_fetch_assoc($res)):
          ?>
            <tr>
              <td><?= $row['product_id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= $row['total_sold'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
