<?php
session_start();
include('../includes/connect.php');

// Any redirect logic BEFORE any HTML

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
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
    <div class="container-fluid m-3">
        <h2 class="text-center">New Registration</h2>
        <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-12 col-xl-6">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-4">
                <!--user name field-->
                <label for="user_name" class="form-label">Name</label>
                <input type="text" id="user_name"class="form-control" placeholder="Enter your name"
                autocomplete="off"required="required" name="user_name"/>

            </div>
            <div class="form-outline mb-4">
                <!--user  surname field-->
                <label for="user_surname" class="form-label">Surname</label>
                <input type="text" id="user_surname"class="form-control" placeholder="Enter your surname"
                autocomplete="off"required="required" name="user_surname"/>

            </div>
            <div class="form-outline mb-4">
                <!--user email-->
                <label for="user_email" class="form-label">Email</label>
                <input type="email" id="user_email"class="form-control" placeholder="Enter your ename"
                autocomplete="off"required="required" name="user_email"/>

            </div>

            <div>
                <label class="form-outline ">Role</label>
                <select name="role" class="form-select">
                    <option value="customer">Customer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">admin</option>
                    <option value="consultant_admin">Consultant</option>
                </select>
            </div>

            <div class="form-outline mb-4">
                <!--user image-->
                <label for="user_image" class="form-label">Image</label>
                <input type="file" id="user_image"class="form-control" 
               required="required" name="user_image"/>

            </div>

            <div class="form-outline mb-4">
                <!--user password-->
                <label for="user_password" class="form-label">Password</label>
                <input type="password" id="user_password"class="form-control" placeholder="Enter your Password"
                autocomplete="off"required="required" name="user_password"/>

            </div>

            <div class="form-outline mb-4">
                <!--user confrim password-->
                <label for="conf_user_password" class="form-label">Confirm Password</label>
                <input type="password" id="conf_user_password"class="form-control" placeholder="Confirm Password"
                autocomplete="off"required="required" name="conf_user_password"/>

            </div>
            <div class="form-outline">
                <!--user phone-->
                <label for="user_phone" class="form-label">Phone</label>
                <input type="text" id="user_phone"class="form-control" placeholder="Enter your contact details"
                autocomplete="off"required="required" name="user_phone"/>

            </div>

            <div class="form-outline mb-4">
                <!--street field-->
                <label for="street" class="form-label">Street</label>
                <input type="text" id="street"class="form-control" placeholder="Enter your Street"
                autocomplete="on"required="required" name="street"/>

            </div>

            <div class="form-outline mb-4">
                <!--user  City field-->
                <label for="city" class="form-label">City</label>
                <input type="text" id="user_surname"class="form-control" placeholder="Enter your City"
                autocomplete="on"required="required" name="city"/>

            </div>

            <div class="form-outline mb-4">
                <!--user  state field-->
                <label for="state" class="form-label">State</label>
                <input type="text" id="state"class="form-control" placeholder="Enter your State"
                autocomplete="on"required="required" name="state"/>

            </div>

            <div class="form-outline mb-4">
                <!--user  postal code field-->
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" id="postal_code"class="form-control" placeholder="Enter your Postal Code"
                autocomplete="off"required="required" name="postal_code"/>

            </div>

            <div class="form-outline mb-4">
                <!--user  country field-->
                <label for="country" class="form-label">Country</label>
                <input type="text" id="country"class="form-control" placeholder="Enter your Country"
                autocomplete="on"required="required" name="country"/>

            </div>

          

         
            </div>
           
            <div class="mt-4 pt-2">
                <input type="submit" value="Register" class="bg-info py-2 px-3
                border-0 "name="user_register"></input>
                <p class="small fw-bold mt-2 ">Already have an account? <a href="user_login.php" >Login</a></p>
            </div>


        </form>
        </div>
</div>


    </div>
</body>
</html>

<?php

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if(isset($_POST['user_register'])){
    $user_name=$_POST['user_name'];
    $user_surname=$_POST['user_surname'];
    $user_email=$_POST['user_email'];
    $user_role=$_POST['role'];
    $user_image=$_FILES['user_image']['name'];
    $user_image_tmp=$_FILES['user_image']['tmp_name'];
    $user_password=$_POST['user_password'];
    $conf_user_password=$_POST['conf_user_password'];
    $user_phone=$_POST['user_phone'];

     //inserting addressess
     $street=$_POST['street'];
     $city=$_POST['city'];
     $state=$_POST['state'];
     $postal_code=$_POST['postal_code'];
     $country=$_POST['country'];

      // 2. Handle file upload
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    move_uploaded_file($user_image_tmp, "./user_images/$user_image");


    

   
    // Password confirmation
    if ($user_password !== $conf_user_password) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit();
    }
    

    //password hashing
    $password_hash = password_hash($user_password, PASSWORD_DEFAULT);

    //insert query
    move_uploaded_file($user_image_tmp,"./user_images/$user_image");
    $insert_query = "INSERT INTO users (first_name, last_name, email, password_hash, role, created_at, updated_at, user_phone, user_image)
     VALUES ('$user_name', '$user_surname', '$user_email', '$password_hash', '$user_role', NOW(), NOW(), '$user_phone', '$user_image')";
     
      
    $sql_execute=mysqli_query($con,$insert_query);

    if($sql_execute){
        echo"<script>alert('Data inserted successfully')</script>";
    }else{
        die(mysqli_error($con));
    }

    $user_id=mysqli_insert_id($con);

    if($user_role=='customer'){
        $insert_query="INSERT INTO customers (user_id) value ($user_id)";
        $sql_execute=mysqli_query($con,$insert_query);

    }elseif($user_role=='seller'){
        $insert_query="INSERT INTO sellers (user_id) value ($user_id)";
        $sql_execute=mysqli_query($con,$insert_query);

    }elseif($user_role=='consultant_admin'){
        $insert_query="INSERT INTO consultant_admins (user_id) value ($user_id)";
        $sql_execute=mysqli_query($con,$insert_query);

    }elseif($user_role=='admin'){
        $insert_query="INSERT INTO admins (user_id) value ($user_id)";
        $sql_execute=mysqli_query($con,$insert_query);
    }
   /* if($sql_execute){
        echo"<script>alert('Data inserted successfully into enums')</script>";
    }else{
        die(mysqli_error($con));
    }*/
   

   

    $insert_address = "INSERT INTO addresses (user_id, street, city, state, postal_code, country)
    VALUES ($user_id, '$street', '$city', '$state', '$postal_code', '$country')";
        $sql_execute=mysqli_query($con,$insert_address);

        echo "<script>alert('Registration successful!')</script>";

        // Redirect based on role
        if ($user_role == 'customer') {
            echo "<script>window.open('../users_area/user_login.php')</script>";
            exit();

        }elseif ($user_role == 'seller') {
            echo "<script>
                alert('Please log in to fill in your business information.');
                window.location.href = '../users_area/user_login.php';
            </script>";
            exit();
        }
        
         elseif ($user_role == 'consultant_admin') {
            echo "<script>
            alert('Registration successful please login.')
            window.location.href = '../users_area/user_login.php')</script>";
            exit();

        } elseif ($user_role == 'admin') {
            echo "<script>
            alert('Registration successful please login.')
            window.location.href = '../users_area/user_login.php')</script>";
            exit();
        }

    }

    
?>