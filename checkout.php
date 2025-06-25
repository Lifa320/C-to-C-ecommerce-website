<?php
session_start();
include('includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: users_area/user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Step 1: Fetch cart_id for the user
$cart_id = null;
$stmt = $con->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($cart_id);
$stmt->fetch();
$stmt->close();

if (!$cart_id) {
    die("No cart found for user.");
}

// Step 2: Fetch cart items and calculate total
$items = [];
$total = 0;

$stmt = $con->prepare("SELECT ci.product_id, ci.quantity, p.price 
                       FROM cart_items ci 
                       JOIN products p ON ci.product_id = p.product_id 
                       WHERE ci.cart_id = ?");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['quantity'] * $row['price'];
    $items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
    $total += $subtotal;
}
$stmt->close();

if (empty($items)) {
    die("Cart is empty.");
}

// Step 3: Insert into Orders
$order_stmt = $con->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
$order_stmt->bind_param("id", $user_id, $total);
$order_stmt->execute();
$order_id = $order_stmt->insert_id;
$order_stmt->close();

// Step 4: Insert into Order_Items
$item_stmt = $con->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($items as $item) {
    $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $item_stmt->execute();
}
$item_stmt->close();

// Step 5: Clear the user's cart
$con->prepare("DELETE FROM cart_items WHERE cart_id = ?")->execute([$cart_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Website Checkout</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Navbar -->
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
                    </ul>
                    <form class="d-flex" action="search_product.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                        <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
                    </form>
                </div>
            </div>
        </nav>

        <!-- User Greeting -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./customer_area/index.php">Welcome, 
                        <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Content Area -->
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <!-- Payment or cart details would be shown via payment.php -->
                      <p class="small fw-bold mt-2 ">Payment Processed <a href="./customer_area/order.php" >view Orders</a></p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include("./includes/footer.php"); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
