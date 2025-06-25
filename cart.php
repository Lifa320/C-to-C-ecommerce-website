<?php
// Start the session and include necessary files
session_start();
include('includes/connect.php');
include('functions/common_function.php');



// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Retrieve or create the cart for the user
$cart_query = "SELECT cart_id FROM carts WHERE user_id = ?";
$stmt = $con->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($cart_id);
    $stmt->fetch();
} else {
    $insert_cart = "INSERT INTO carts (user_id, created_at) VALUES (?, NOW())";
    $stmt_insert = $con->prepare($insert_cart);
    $stmt_insert->bind_param("i", $user_id);
    $stmt_insert->execute();
    $cart_id = $stmt_insert->insert_id;
    $stmt_insert->close();
}
$stmt->close();

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $cart_item_id => $quantity) {
            $update_query = "UPDATE cart_items SET quantity = ? WHERE cart_item_id = ? AND cart_id = ?";
            $stmt_update = $con->prepare($update_query);
            $stmt_update->bind_param("iii", $quantity, $cart_item_id, $cart_id);
            $stmt_update->execute();
            $stmt_update->close();
        }
    }

    // Handle item removals
    if (isset($_POST['remove_cart']) && isset($_POST['removeitem'])) {
        foreach ($_POST['removeitem'] as $cart_item_id) {
            $delete_query = "DELETE FROM cart_items WHERE cart_item_id = ? AND cart_id = ?";
            $stmt_delete = $con->prepare($delete_query);
            $stmt_delete->bind_param("ii", $cart_item_id, $cart_id);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
    }

    // Redirect to avoid form resubmission
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce Website Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .cart_img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
               <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="LOGO.jpeg" alt="Site Logo" style="height: 40px; width: auto; margin-right: 10px;">
        <span class="text-white fw-bold">Iconic-Base</span>
      </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>   
                </button>    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="./customer_area/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="./customer_area/display_all.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="./customer_area/order.php">Orders</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <sup><?php cart_item(); ?></sup>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Welcome Message -->
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

        <!-- Cart Table -->
        <div class="container m-2 px-2">
        <div class="row">
        <form action="" method="post">
        <table class="table table-bordered text-center">
         <?php
         $total = 0;
         $cart_items_query = "
         SELECT ci.cart_item_id, ci.quantity, p.product_id, p.name, p.image, p.price
         FROM cart_items ci
         JOIN products p ON ci.product_id = p.product_id
         WHERE ci.cart_id = ?
         ";
        $stmt_items = $con->prepare($cart_items_query);
        $stmt_items->bind_param("i", $cart_id);
        $stmt_items->execute();
        $result = $stmt_items->get_result();
        if ($result->num_rows > 0) {
         echo "
         <thead>
         <tr>
         <th>Product Name</th>
         <th>Product Image</th>
         <th>Quantity</th>
         <th>Total Price</th>
         <th>Remove</th>
          </tr>
          </thead>
         <tbody>
         ";
         while ($row = $result->fetch_assoc()) {
         $subtotal = $row['price'] * $row['quantity'];
         $total += $subtotal;
$image_path = "./seller_area/product_images/" . htmlspecialchars($row['image']);
         echo "
         <tr>
         <td>{$row['name']}</td>
        <td><img src='$image_path' class='card-img-top' alt='" . htmlspecialchars($row['name']) . "' style='width: 70px; height: 70px; object-fit: cover;'></td>
         <td>
         <input type='number' name='quantities[{$row['cart_item_id']}]' value='{$row['quantity']}' min='1' class='form-control w-50 mx-auto'>
         </td>
         <td>R{$subtotal}</td>
         <td>
         <input type='checkbox' name='removeitem[]' value='{$row['cart_item_id']}'>
         </td>
         </tr>
         ";
          }
         echo "</tbody></table>";
         echo "
         <h4 class='px-3'>Subtotal: R<strong class='text-info'> $total</strong></h4>
         <div class='d-flex justify-content-between'>
         <input type='submit' name='update_cart' value='Update Cart' class='btn btn-info'>
         <input type='submit' name='remove_cart' value='Remove Selected' class='btn btn-danger'>
         <a href='checkout.php' class='btn btn-success' onclick='return confirm('Are you sure you want to place the order?')'>Place Order</a>

          </div>
         ";
         } else {
         echo "<h2 class='text-center text-danger'>Cart is empty</h2>";
          }
         $stmt_items->close();
         ?>
                    </table>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include("./includes/footer.php"); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
