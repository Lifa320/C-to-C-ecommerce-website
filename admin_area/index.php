<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
session_start();
include('../includes/connect.php'); 

if (!isset($_SESSION['user_id'])) {
    header("..users_area/user_login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Admin Dashboard</title>
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
        <body class="d-flex flex-column min vh-100">
            <h1>Admin</h1>
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php?dashboard">Admin Dashboard</a>
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
              Welcome, Admin
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

<!-- Rewritten Sidebar using Button Groups -->
<div class="d-flex bg-light p-2 justify-content-start flex-wrap">
  <!-- Dashboard -->
  <a class="btn btn-outline-none me-2 mb-2" href="index.php?dashboard">
    <i class="fas fa-home me-1"></i> Dashboard
  </a>
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-box me-1"></i> Business
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?view_business">View Businesses</a></li>
     
    </ul>
  </div>

  <!-- My Products Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-box me-1"></i> Products
    </button>
    <ul class="dropdown-menu">
    
      <li><a class="dropdown-item" href="index.php?view_products">View Products</a></li>
    </ul>
  </div>

  <!-- Product Offers Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-tags me-1"></i> Category 
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?insert_categories">Add Categories</a></li>
      <li><a class="dropdown-item" href="index.php?view_categories">View Categories</a></li>
     
    </ul>
  </div>

  <!-- Reports Dropdown -->
  <div class="btn-group me-2 mb-2">
    <button type="button" class="btn btn-outline-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-chart-line me-1"></i> Reports
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="index.php?view_sellers">View Sellers</a></li>
      <li><a class="dropdown-item" href="index.php?view_customers">View Customers</a></li>
      <li><a class="dropdown-item" href="index.php?view_admin_consultants">View Consultant Admins</a></li>
      <li><a class="dropdown-item" href="index.php?product_analysis">Products Analysis</a></li>
    </ul>
  </div>

<!-- view products-->
<?php
if (isset($_GET['view_products'])) {
    include('view_products.php');
}
?>

<!-- product analysis-->
<div class="container my-3">
  <div class="row">
    <div class="col-12">
      <?php
      if (isset($_GET['product_analysis'])) {
          include('product_analysis.php');
      }
      ?>
    </div>
  </div>
</div>


<!-- view consultant admins-->
<?php
if (isset($_GET['view_admin_consultants'])) {
    include('view_admin_consultants.php');
}
?>

          <!--view categories  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['view_categories'])){
    include('view_categories.php');
}
    ?>

  </div>


<!--inserting into categorie-->
<div class ="container my-3">
    <?php
    if(isset($_GET['insert_categories'])){
      include('insert_categories.php');

    }
    ?>

    <!--view businesses-->
<div class ="container my-3">
    <?php
    if(isset($_GET['view_business'])){
      include('view_business.php');

    }
    ?>

  </div>

            <!--view sellers  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['view_sellers'])){
    include('view_sellers.php');
}
    ?>

                <!--view customers  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['view_customers'])){
    include('view_customers.php');
}
    ?>

  </div>

              <!--view sellers products  -->
  <div class ="container my-3">
    <?php
  if (isset($_GET['view_seller_products'])) {
    $seller_id = intval($_GET['view_seller_products']);
    include('../consultant_area/view_seller_products.php');
}
    ?>

  </div>
       <!--dashboard  -->
  <div class ="container my-3">
    <?php
   if(isset($_GET['dashboard'])){
    include('dashboard.php');
}
?>
</div>

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