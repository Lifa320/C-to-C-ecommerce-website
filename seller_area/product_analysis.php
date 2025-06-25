<?php
include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../users_area/user_login.php");
        exit();
    }
}

$user_id = $_SESSION['user_id'];
?>

<link rel="stylesheet" href="../style.css">

<div class="justify-content:center p-4">
  <h2>Seller Product Analytics</h2>

  <ul class="nav nav-tabs" id="sellerAnalyticsTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="most-units-tab" data-bs-toggle="tab" data-bs-target="#most-units" type="button" role="tab">Most Product Units Sold</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="least-units-tab" data-bs-toggle="tab" data-bs-target="#least-units" type="button" role="tab">Least Units Sold</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="most-revenue-tab" data-bs-toggle="tab" data-bs-target="#most-revenue" type="button" role="tab">Most Revenue Products</button>
    </li>
  </ul>

  <div class="tab-content mt-3" id="sellerAnalyticsTabContent">

    <!-- Most Product Units Sold -->
    <div class="tab-pane fade show active" id="most-units" role="tabpanel">
      <h5>Products with the Most Units Sold</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Total Units Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT p.product_id, p.name, SUM(oi.quantity) AS total_sold
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE p.user_id = $user_id
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

    <!-- Least Units Sold -->
    <div class="tab-pane fade" id="least-units" role="tabpanel">
      <h5>Products with the Least Units Sold</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Total Units Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT p.product_id, p.name, IFNULL(SUM(oi.quantity), 0) AS total_sold
                FROM products p
                LEFT JOIN order_items oi ON p.product_id = oi.product_id
                WHERE p.user_id = $user_id
                GROUP BY p.product_id
                ORDER BY total_sold ASC";
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

    <!-- Most Revenue Products -->
    <div class="tab-pane fade" id="most-revenue" role="tabpanel">
      <h5>Products Generating the Most Revenue</h5>
      <table class="table table-bordered text-center">
        <thead class="table-info">
          <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Total Revenue (R)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $q = "SELECT p.product_id, p.name, SUM(oi.quantity * oi.price) AS total_revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE p.user_id = $user_id
                GROUP BY p.product_id
                ORDER BY total_revenue DESC";
          $res = mysqli_query($con, $q);
          while ($row = mysqli_fetch_assoc($res)):
          ?>
            <tr>
              <td><?= $row['product_id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td>R <?= number_format($row['total_revenue'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
