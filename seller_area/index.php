<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../includes/connect.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$seller_id = $_SESSION['user_id'];
$check_business = "SELECT * FROM business_requests WHERE user_id = '$user_id'";
$result = mysqli_query($con, $check_business);

if (mysqli_num_rows($result) == 0) {
    // No business found
    header("Location: business_registration.php");
    exit();
}

if (isset($_GET['update_shipping']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $shipping_date = $_POST['shipping_date'] ?? null;
    $delivery_date = $_POST['delivery_date'] ?? null;
    $shipping_status = $_POST['shipping_status'] ?? 'pending';
    $courier_name = trim($_POST['courier_name'] ?? '');
    $tracking_number = trim($_POST['tracking_number'] ?? '');

    if (!$order_id || !$shipping_date || !$delivery_date || !$courier_name || !$tracking_number) {
        die("All shipping fields are required.");
    }

    // Get customer (user_id) from Orders table using order_id, then get address_id
    $stmt = $con->prepare("
        SELECT u.user_id, a.address_id
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN addresses a ON u.user_id = a.user_id
        WHERE o.order_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $address_result = $stmt->get_result();
    $address_row = $address_result->fetch_assoc();

    $shipping_address_id = $address_row['address_id'] ?? NULL;

    // Verify that the seller owns at least one product in the order
    $stmt = $con->prepare("
        SELECT 1 FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ? AND p.user_id = ? LIMIT 1
    ");
    $stmt->bind_param("ii", $order_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid order or permission denied.");
    }

    // Check if shipping record exists
    $stmt = $con->prepare("SELECT shipping_id FROM shipping WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if ($existing) {
        // Update existing
        $stmt = $con->prepare("
            UPDATE shipping SET
                shipping_address_id = ?,
                shipping_date = ?,
                delivery_date = ?,
                shipping_status = ?,
                courier_name = ?,
                tracking_number = ?
            WHERE order_id = ?
        ");
        $stmt->bind_param("isssssi", $shipping_address_id, $shipping_date, $delivery_date, $shipping_status, $courier_name, $tracking_number, $order_id);
        $stmt->execute();
    } else {
        // Insert new record
        $stmt = $con->prepare("
            INSERT INTO shipping
            (order_id, shipping_address_id, shipping_date, delivery_date, shipping_status, courier_name, tracking_number)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssss", $order_id, $shipping_address_id, $shipping_date, $delivery_date, $shipping_status, $courier_name, $tracking_number);
        $stmt->execute();
    }

    // If shipping status is delivered, update order items and possibly order status
    if ($shipping_status === 'delivered') {
        // Update order_items status to delivered for this seller and order
        $updateOrderItems = $con->prepare("
            UPDATE order_items oi
            JOIN products p ON oi.product_id = p.product_id
            SET oi.status = 'delivered'
            WHERE oi.order_id = ? AND p.user_id = ?
        ");
        $updateOrderItems->bind_param("ii", $order_id, $seller_id);
        $updateOrderItems->execute();

        // Check if all order_items are delivered
        $checkAllDelivered = $con->prepare("
            SELECT COUNT(*) AS undelivered_count
            FROM order_items
            WHERE order_id = ? AND status != 'delivered'
        ");
        $checkAllDelivered->bind_param("i", $order_id);
        $checkAllDelivered->execute();
        $result = $checkAllDelivered->get_result()->fetch_assoc();

        if ($result['undelivered_count'] == 0) {
            $updateOrder = $con->prepare("
                UPDATE orders
                SET status = 'complete'
                WHERE order_id = ?
            ");
            $updateOrder->bind_param("i", $order_id);
            $updateOrder->execute();
        }
    }

    // Redirect to accepted orders view (avoid resubmission)
    header("Location: index.php?accepted_orders");
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Seller Dashboard</title>
        <!-- bootstrap CSS link-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" 
        crossorigin="anonymous">

        <!--font awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
         integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
         crossorigin="anonymous" 
         referrerpolicy="no-referrer" />
        <!--css file-->
        <link rel="stylesheet"href="../style.css">
      
        </head>
        <body>
           
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="../LOGO.jpeg" alt="Site Logo" style="height: 40px; width: auto; margin-right: 10px;">
        <span class="text-white fw-bold">Iconic-Base</span>
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarContent"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div
        class="collapse navbar-collapse justify-content-end"
        id="navbarContent"
      >
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="userDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Welcome, Seller
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#account-overview">Account Overview</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="d-flex bg-light p-2 justify-content-start flex-wrap">
  <!-- Dashboard -->
  <a class="btn btn-outline-none me-2 mb-2" href="index.php?Dashboard">
    <i class="fas fa-home"></i> Dashboard
  </a>

  <!-- My Products Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-box"></i> My Products
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?insert_products">Insert Products</a></li>
      <li><a class="dropdown-item" href="index.php?view_products">View Products</a></li>
    </ul>
  </div>

  <!-- Product Offers Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-tags"></i> Category 
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?view_categories">View Categories</a></li>
      
    </ul>
  </div>

  <!-- Orders Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-shopping-cart"></i> Orders
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?all_orders">All Orders</a></li>
        <li><a class="dropdown-item" href="index.php?accepted_orders">Accepted Orders</a></li>
      <li><a class="dropdown-item" href="#">All Payments</a></li>
    </ul>
  </div>

  <!-- Reports -->
 <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-shopping-cart"></i> Reports
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?product_analysis">Product Analysis</a></li>
      </ul>
  </div>
 
 

  </div>
  <div class="container">
 
  
</div>



  <div class ="container">
 

  </div>
 
  <?php
    if(isset($_GET['Dashboard'])){
      include('Dashboard.php');

    }
    ?>

<?php
if (isset($_GET['view_products'])) {
    include('view_products.php');
}
?>
<!--product analysis-->
<?php
if (isset($_GET['product_analysis'])) {
    include('product_analysis.php');
}
?>
          <!--view categories  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['view_categories'])){
    include('view_categories.php');
}


    ?>
              <!--view categories  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['update_shipping'])){
    include('update_shipping.php');
}
?>

             <!--view orders  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['all_orders'])){
    include('all_orders.php');
}
    ?>



             <!--accepted orders  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['accepted_orders'])){
    include('accepted_orders.php');
}
    ?>



  </div>
  <div class="container">
    <?php
    if(isset($_GET['insert_products'])){
      include('insert_products.php');
    }
    ?>
</div>

<?php

  //editing product and deleting product
if (isset($_GET['edit_product'])) {
  $product_id = $_GET['edit_product'];
  $user_id = $_SESSION['user_id'];
  
  $get_seller = "SELECT seller_id FROM sellers WHERE user_id = '$user_id'";
  $run_seller = mysqli_query($con, $get_seller);
  $row_seller = mysqli_fetch_assoc($run_seller);
  $seller_id = $row_seller['seller_id'];

  $get_product = "SELECT * FROM products WHERE product_id = '$product_id' AND user_id = '$user_id'";
  $edit_result = mysqli_query($con, $get_product);

  if ($edit_result && mysqli_num_rows($edit_result) > 0) {
      $edit_row = mysqli_fetch_assoc($edit_result);
      ?>
      <hr>
      <h3>Edit Product</h3>
      <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="product_id" value="<?php echo $edit_row['product_id']; ?>">

          <div class="mb-3">
              <label>Description:</label>
              <textarea name="description" class="form-control"><?php echo $edit_row['description']; ?></textarea>
          </div>

          <div class="mb-3">
              <label>Stock Quantity:</label>
              <input type="number" name="stock_quantity" value="<?php echo $edit_row['stock_quantity']; ?>" class="form-control">
          </div>

          <div class="mb-3">
    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?php echo $edit_row['price']; ?>" class="form-control">
</div>

          <div class="mb-3">
              <label>Change Image:</label>
              <input type="file" name="image" class="form-control">
              <br>
              <img src="./product_images/<?php echo $edit_row['image']; ?>" width="100">
          </div>

          <input type="submit" name="update_product" value="Update Product" class="btn btn-primary">
      </form>
      <?php
  } else {
      echo "<p class='text-danger'>Product not found or access denied.</p>";
  }
}

// Handle the update
if (isset($_POST['update_product'])) {
  $product_id = $_POST['product_id'];
  $description = $_POST['description'];
  $stock_quantity = $_POST['stock_quantity'];
  $price=$_POST['price'];
  $image = $_FILES['image']['name'];
  $temp_image = $_FILES['image']['tmp_name'];

  if (!empty($image)) {
    move_uploaded_file($temp_image, "./product_images/$image");
    $update_query = "UPDATE products 
        SET description='$description', stock_quantity='$stock_quantity', price='$price', image='$image' 
        WHERE product_id='$product_id'";
} else {
    $update_query = "UPDATE products 
        SET description='$description', stock_quantity='$stock_quantity', price='$price' 
        WHERE product_id='$product_id'";
}


  $result = mysqli_query($con, $update_query);

  if ($result) {
      echo "<script>alert('Product updated successfully')</script>";
      echo "<script>window.location.href='index.php?view_products'</script>";
  } else {
      echo "<p class='text-danger'>Failed to update product.</p>";
  }
}

?>




<div class="bg-info p-3 text-center mt-auto">
<p class=" mb-0">All rights reserved ©- Designed by Ndinda-2025</p>
</div>
  </div>



  
              <!--bootstrap js link-->
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" 
            crossorigin="anonymous"></script>
        </body>
        </html>



