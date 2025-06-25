<!--connect to file-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: ../index.php");
    exit();
}

include('../includes/connect.php');
include('../functions/common_function.php');
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Ecommerce Website using PHP and MySQL</title>
        <!-- bootstrap CSS link-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" 
        crossorigin="anonymous">
         <!--font awesome link-->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
         integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
         crossorigin="anonymous" 
         referrerpolicy="no-referrer" />
         <link rel="stylesheet" href="style.css">
         </head>
        <body>
            <!--navbar-->
            <div class="container-fluid p-0">
  <!-- First child -->
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
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="display_all.php">Products</a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order.php">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><sup><?php cart_item();?></sup></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Total Price: R<?php total_cart_price();?> </a>
            </li>
        </ul>
        <form class="d-flex" action="search_product.php" method="get">
            <input class ="form-control me-2"type="search"placeholder="Search"aria-label="Search" name="search_data">
           <!-- <button class="btn btn-outline-success"type="submit">Search</button> -->
            <input type="submit" value="Search"class="btn btn-outline-light"name="search_data_product">
        </form>
      </div>
    
    
  </div>
</nav>
<!-- calling cart function-->
 <?php
 cart();
 ?>

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



<div class="row  mt-3">
    <div class="col-md-10">
<!--products-->
       <div class="row">
        <!--fetching products-->
        <?php
        search_product();
      getproducts();
        get_specific_cate();
     
        ?>
   
  <!--row end-->
       </div>
       <!--col end-->
    </div>
    <div class="col-md-2 bg-primary p-0">
        <!--sidepanel-->
        <ul class="navbar-nav me-auto text-center">
            <li class="nav-item bg-secondary">
                <a href="#" class="nav-link text-light"><h6>Brands</h6></a>
           </li>                
        </ul>
        <ul class="navbar-nav me-auto text-center">
            <li class="nav-item bg-secondary">
                <a href="#" class="nav-link text-light"><h6>Categories</h6></a>
            </li>
            <?php
            //displaying categories
            getcategories();          
            ?>             
        </ul>
    </div>
</div>

<!--footer-->
<?php include("../includes/footer.php") ?>

            </div>

            <!--bootstrap js link-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" 
            crossorigin="anonymous"></script>
        </body>
    </head>
</html>