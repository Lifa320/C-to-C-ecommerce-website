<?php


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include('../includes/connect.php');

if (isset($_POST['insert_product'])) {
    $user_id = $_SESSION['user_id'];
    $seller_id = $_SESSION['user_id'];
 

    if (!$seller_id) {
        echo "<script>alert('Seller account not found.')</script>";
        exit();
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $image = $_FILES['image']['name'];
    $keyword = $_POST['keyword'];
    $category = $_POST['category'];

    $temp_image = $_FILES['image']['tmp_name'];

    if ($user_id == '' || $name == '' || $description == '' || $price == ''
        || $stock_quantity == '' || $image == '' || $keyword == '' || $category == '') {
        echo "<script>alert('Please fill all available fields')</script>";
        exit();
    } else {
        move_uploaded_file($temp_image, "./product_images/$image");

        $insert_product = "INSERT INTO products(user_id, name, description, price, stock_quantity, image,
        created_at, keyword, category_id) VALUES('$seller_id', '$name', '$description', $price, '$stock_quantity', '$image',
        NOW(), '$keyword', '$category')";
        
        $result_query = mysqli_query($con, $insert_product);
        
        if ($result_query) {
            echo "<script>alert('Successfully inserted product')</script>";
        } else {
            echo "<script>alert('Error inserting product')</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"conent="IE=edge">
    <meta name="viewport"content="width=device-width,initial-scale=1.0">
    <title>Insert Products-Seller Dashboard</title>
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
  <body class="bg-light">
    <div class="container mt-3">
      <h1 class="text-center ">Insert Products</h1>

      
      <form action="" method="post" enctype="multipart/form-data">
        <!--name-->
        <div class="form-outline mb-4 w-50 m-auto">
          <label for="name" class="form-label">Product Name</label>
          <input type="text"name="name"id="name"class="form-control"placeholder="Enter product Name"autocomplete="off"
          required="required">
        </div>
         <!--description-->
         <div class="form-outline mb-4 w-50 m-auto">
          <label for="desrciption" class="form-label">Product description</label>
          <input type="text"name="description"id="description"class="form-control"placeholder="Enter product description"autocomplete="off"
          required="required">
        </div>
        <!--Price-->
        <div class="form-outline mb-4 w-50 m-auto">
          <label for="price" class="form-label">Product Price</label>
          <input type="text"name="price"id="price"class="form-control"placeholder="Enter product price"autocomplete="off"
          required="required">
        </div>

 <!--quantity-->
 <div class="form-outline mb-4 w-50 m-auto">
          <label for="stock_quantity" class="form-label">Product Stock Quantity</label>
          <input type="text"name="stock_quantity"id="stock_quantity"class="form-control"placeholder="Enter product stock quantity"autocomplete="off"
          required="required">
        </div>

         <!--keyword-->
         <div class="form-outline mb-4 w-50 m-auto">
          <label for="keyword" class="form-label">Product Keyword</label>
          <input type="text"name="keyword"id="keyword"class="form-control"placeholder="Enter product keyword"autocomplete="off"
          required="required">
        </div>
          <!--categories-->
          <div class="form-outline mb-4 w-50 m-auto">
          <select name="category"id="" class="form-select">
            <option value=""> Select Category</option>
            <?php
            $select_query="Select *from categories";
            $result_query=mysqli_query($con,$select_query);
            while($row=mysqli_fetch_assoc($result_query)){
             
              $category_id=$row['category_id'];
              $name=$row['name'];
              echo "<option value='$category_id'> $name</option>";
            }


            ?>
          
           
          </select>
          </div>
        
          </select>
          </div>
           <!--Image 1-->
         <div class="form-outline mb-4 w-50 m-auto">
          <label for="image" class="form-label">Product Image </label>
          <input type="file"name="image"id="image"class="form-control"
          required="required">
          </div>
        
        <div class="form-outline mb-4 w-50 m-auto">
         <input type="submit"name="insert_product"class="btn btn-info mb-3 px-3"value="Insert Product">
        </div>
        

      </form> 


    </div>
  </body>
</html>
