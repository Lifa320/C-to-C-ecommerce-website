<?php 
include('../includes/connect.php');
session_start();

$error = "";

if (isset($_POST['user_login'])) {
    $email = mysqli_real_escape_string($con, trim($_POST['user_email']));
    $password = $_POST['user_password'];

    $query = "SELECT user_id, first_name, email, password_hash, role FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password_hash'])) {
            // Store all needed session variables after verifying password
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];

            
            // Redirect based on role
            switch($user['role']) {
                case 'admin':
                    header("Location: ../admin_area/index.php");
                    break;
                    case 'consultant_admin':
                        header("Location: ../consultant_area/index.php");
                        break;
                case 'seller':
                    $seller_id = $user['user_id'];
                    $_SESSION['seller_id'] = $seller_id;
                    $status_query = "SELECT status FROM business_requests WHERE user_id = '$seller_id' ORDER BY submitted_at DESC LIMIT 1";
                    $status_result = mysqli_query($con, $status_query);

                    if($status_result && mysqli_num_rows($status_result) > 0) {
                        $status_row = mysqli_fetch_assoc($status_result);
                        $status = strtolower($status_row['status']);

                        if($status === 'pending') {
                            header("Location: ../seller_area/pending.php");
                            exit();
                        } elseif($status === 'approved') {
                            echo "<script>alert('your business is approved.');</script>";
                            header("Location: ../seller_area/index.php");
                        } else {
                            echo "<script>alert('Your registration was rejected or is undefined. Contact admin.');</script>";
                            exit();
                        }
                    } else {
                        echo "<script>alert('No business request found. Please register your business.');</script>";
                        header("refresh:2; url=../seller_area/business_registration.php");
                        exit();
                    }
                    break;
                case 'customer':
                    header("Location: ../customer_area/index.php");
                    break;
                default:
                    header("Location: ../index.php");
                    break;
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
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
        <h2 class="text-center">User Login</h2>
        <div class="row d-flex align-items-center justify-content-center mt-5">
        <div class="col-lg-12 col-xl-6">
        <form action="" method="post" enctype="multipart/form-data">
           
            <div class="form-outline mb-4">
                <!--user email-->
                <label for="user_email" class="form-label">Email</label>
                <input type="email" id="user_email"class="form-control" placeholder="Enter your ename"
                autocomplete="off"required="required" name="user_email"/>

            </div>
             
            <div class="form-outline mb-4">
                <!--user password-->
                <label for="user_password" class="form-label">Password</label>
                <input type="password" id="user_password"class="form-control" placeholder="Enter your Password"
                autocomplete="off"required="required" name="user_password"/>

              <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword()" style="cursor: pointer;">
        
    </span>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById("user_password");
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
}
</script>
       
            <div class="mt-4 pt-2">
                <input type="submit" value="Login" class="bg-info py-2 px-3
                border-0 "name="user_login"></input>
                <p class="small fw-bold mt-2 ">Don't have an account? <a href="user_registration.php" >Register</a></p>
            </div>


        </form>
        </div>
</div>


    </div>
</body>
</html>


