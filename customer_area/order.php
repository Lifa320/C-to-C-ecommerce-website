<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: users_area/user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - Ecommerce Website</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                 <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="../LOGO.jpeg" alt="Site Logo" style="height: 40px; width: auto; margin-right: 10px;">
        <span class="text-white fw-bold">Iconic-Base</span>
      </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>   
                </button>    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="display_all.php">Products</a></li>
                       
                        <li class="nav-item"><a class="nav-link active" href="#">Orders</a></li>
                    </ul>
                    <form class="d-flex" action="search_product.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                        <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
                    </form>
                </div>
            </div>
        </nav>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav me-auto">
    <li class="nav-item">
      <a class="nav-link md-3" href="#">
        <?php
          if (isset($_SESSION['first_name'])) {
              echo "Welcome " . htmlspecialchars($_SESSION['first_name']);
          } else {
              echo "Welcome guest";
          }
        ?>
      </a>
    </li>
  </ul>
</nav>

        <!-- Orders Table -->
        <div class="container mt-4">
            <h2 class="text-center mb-4">Your Orders</h2>
            <?php
            // Fetch orders for the user
            $order_query = $con->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
            $order_query->bind_param("i", $user_id);
            $order_query->execute();
            $order_result = $order_query->get_result();

            if ($order_result->num_rows > 0) {
                while ($order = $order_result->fetch_assoc()) {
                    $order_id = $order['order_id'];
                    $order_date = $order['order_date'];
                    $total = $order['total_amount'];
                    $status = ucfirst($order['status']);
                    echo "<div class='card mb-4'>
                            <div class='card-header bg-primary text-white'>
                                <strong>Order ID:</strong> $order_id | <strong>Date:</strong> $order_date | <strong>Status:</strong> $status
                            </div>
                            <div class='card-body p-0'>
                                <table class='table table-bordered mb-0'>
                                    <thead class='table-light'>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                    // Fetch order items
                    $items_query = $con->prepare("
                        SELECT oi.quantity, oi.price, p.name 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.product_id 
                        WHERE oi.order_id = ?");
                    $items_query->bind_param("i", $order_id);
                    $items_query->execute();
                    $items_result = $items_query->get_result();

                    while ($item = $items_result->fetch_assoc()) {
                        $name = htmlspecialchars($item['name']);
                        $qty = $item['quantity'];
                        $price = number_format($item['price'], 2);
                        $subtotal = number_format($qty * $item['price'], 2);
                        echo "<tr>
                                <td>$name</td>
                                <td>$qty</td>
                                <td>R$price</td>
                                <td>R$subtotal</td>
                              </tr>";
                    }

                    echo "</tbody>
                        </table>
                        </div>
                        <div class='card-footer text-end'>
                            <strong>Total: R" . number_format($total, 2) . "</strong>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='alert alert-info text-center'>You have no orders yet.</p>";
            }
            ?>
        </div>

        <!-- Footer -->
        <?php include("../includes/footer.php"); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
