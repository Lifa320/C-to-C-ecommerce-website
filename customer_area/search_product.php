<?php
include('../includes/connect.php');
include('../functions/common_function.php');
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Ecommerce Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="display_all.php">Products</a></li>
                        <?php if (!isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="../users_area/user_registration.php">Register</a></li>
     <li class="nav-item"><a class="nav-link" href="../users_area/user_login.php">Sign-In</a></li>
<?php endif; ?>
                  <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="index.php?order">Orders</a></li>
       <li class="nav-item"> <a class="nav-link" href="../logout.php">Logout</a></li>
<?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="fa fa-shopping-cart"></i>
                            <sup><?php cart_item(); ?></sup>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Total Price: R<?php total_cart_price(); ?></a>
                    </li>
                </ul>
                <form class="d-flex" action="search_product.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                    <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
                </form>
            </div>
        </div>
    </nav>

    <!-- Welcome message -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link md-3" href="#">
                    <?php
                    echo isset($_SESSION['first_name']) ? "Welcome " . htmlspecialchars($_SESSION['first_name']) : "Welcome guest";
                    ?>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Content -->
    <div class="row mt-3">
        <div class="col-md-10">
            <div class="row">
                <?php
                search_product();
                get_specific_cate();
                ?>
            </div>
        </div>
        <div class="col-md-2 bg-primary p-0">
            <ul class="navbar-nav me-auto text-center">
                <li class="nav-item bg-secondary">
                    <a href="#" class="nav-link text-light"><h6>Categories</h6></a>
                </li>
                <?php getcategories(); ?>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-info p-3 text-center">
        <p>All rights reserved © - Designed by Ndinda - 2025</p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
