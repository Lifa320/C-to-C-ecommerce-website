
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../includes/connect.php');
SESSION_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/

if (isset($_POST['request_business'])) {
    $user_id = $_SESSION['user_id']; // Direct from session




    // Retrieve form data
$business_name = mysqli_real_escape_string($con, $_POST['business_name']);
$owner_name = mysqli_real_escape_string($con, $_POST['owner_name']);
$business_type = mysqli_real_escape_string($con, $_POST['business_type']);
$warehouse_address = mysqli_real_escape_string($con, $_POST['warehouse_address']);
$telephone = mysqli_real_escape_string($con, $_POST['telephone']);


    // Insert into business_requests
$insert_query = "INSERT INTO business_requests (user_id, business_name, owner_name, submitted_at, business_type, warehouse_address, telephone)
VALUES ('$user_id', '$business_name', '$owner_name', NOW(), '$business_type', '$warehouse_address', '$telephone')";


    $sql_execute = mysqli_query($con, $insert_query);

    if ($sql_execute) {
        echo "<script>alert('Data inserted successfully');
        window.location.href = '../users_area/user_login.php;
        </script>";
    } else {
        die(mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login</title>
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
        <h2 class="text-center">Business Registration</h2>
        <div class="row d-flex align-items-center justify-content-center mt-5">
        <div class="col-lg-12 col-xl-6">
        <form action="" method="post" enctype="multipart/form-data">
           
            <div class="form-outline mb-4">
                <!--business name-->
                <label for="business_name" class="form-label">Business name</label>
                <input type="text" id="business_name"class="form-control" placeholder="Enter Business Name"
                autocomplete="off"required="required" name="business_name"/>

            </div>
             
            <div class="form-outline mb-4">
                <!--owner name -->
                <label for="owner_name" class="form-label">Owner name</label>
                <input type="text" id="owner_name"class="form-control" placeholder="Enter Owner Name"
                autocomplete="off"required="required" name="owner_name"/>

            </div>

            <div class="form-outline mb-4">
                <!--telephone -->
                <label for="telephone" class="form-label">Business Telephone</label>
                <input type="text" id="telephone"class="form-control" placeholder="Enter Business Telephone"
                autocomplete="off"required="required" name="telephone"/>

            </div>

            <div class="form-outline mb-4">
                <!--business type -->
                <label for="business_type" class="form-label">Business Type</label>
                <input type="text" id="business_type"class="form-control" placeholder="Enter Business Type"
                autocomplete="off"required="required" name="business_type"/>

            </div>

            
            <div class="form-outline mb-4">
                <!--business address -->
                <label for="warehouse_address" class="form-label">Business warehouse address</label>
                <input type="text" id="warehouse_address"class="form-control" placeholder="Enter Business warehouse address"
                autocomplete="off"required="non-required" name="warehouse_address"/>

            </div>
       
            <div class="mt-4 pt-2">
                <input type="submit" value="Submit" class="bg-info py-2 px-3
                border-0 "name="request_business"></input>
                <p class="small fw-bold mt-2 ">Go back to home page? <a href="../index.php" >Home</a></p>
            </div>


        </form>
        </div>
</div>


    </div>
</body>
</html>