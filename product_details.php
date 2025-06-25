<?php
include('includes/connect.php');
include('functions/common_function.php');
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
    <title>Product Details</title>
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>    
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="display_all.php">Products</a></li>
                  
                   <?php if (!isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="users_area/user_registration.php">Register</a></li>
     <li class="nav-item"><a class="nav-link" href="users_area/user_login.php">Sign-In</a></li>
<?php endif; ?>
                  <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="./customer_area/order.php">Orders</a></li>
       <li class="nav-item"> <a class="nav-link" href="../logout.php">Logout</a></li>
<?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href='cart.php'><i class="fa fa-shopping-cart"></i><sup><?php cart_item(); ?></sup></a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Total Price: R<?php total_cart_price(); ?></a></li>
                </ul>
                <form class="d-flex" action="search_product.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                    <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
                </form>
            </div>
        </div>
    </nav>

    <!-- Welcome -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <?= isset($_SESSION['first_name']) ? "Welcome " . htmlspecialchars($_SESSION['first_name']) : "Welcome guest"; ?>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Product Detail & Review Section -->
    <div class="container mt-4">
        <div class="row">
            <?php
            if (isset($_GET['product_id'])) {
                $product_id = intval($_GET['product_id']);
                $select_query = "SELECT * FROM products WHERE product_id = $product_id";
                $result = mysqli_query($con, $select_query);

                if ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="col-md-6">
                        <img src="./seller_area/product_images/' . htmlspecialchars($row['image']) . '" 
                                 class="small-image" alt="' . htmlspecialchars($row['name']) . '">
                    </div>
                    <div class="col-md-6">
                        <h3>' . htmlspecialchars($row['name']) . '</h3>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                        <h4 class="text-success">R' . number_format($row['price'], 2) . '</h4>
                        <a href="customer_area/index.php?add_to_cart=' . $row['product_id'] . '" class="btn btn-primary mt-2">Add to Cart</a>
                    </div>';
                } else {
                    echo "<p>Product not found.</p>";
                }
            } else {
                echo "<p>No product selected.</p>";
            }
            ?>
        </div>

        <!-- Submit Review -->
        <div class="row mt-4">
            <div class="col-md-8">
                <h4>Leave a Review</h4>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <div class="mb-2">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" name="rating" required>
                            <option value="">-- Select Rating --</option>
                            <option value="1">★☆☆☆☆</option>
                            <option value="2">★★☆☆☆</option>
                            <option value="3">★★★☆☆</option>
                            <option value="4">★★★★☆</option>
                            <option value="5">★★★★★</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="btn btn-success">Submit Review</button>
                </form>
            </div>
        </div>

        <!-- Display Reviews -->
        <div class="row mt-4">
            <div class="col-md-8">
                <h4>Customer Reviews</h4>
                <?php
                $review_query = "SELECT r.*, u.first_name 
                                 FROM reviews r
                                 JOIN users u ON r.user_id = u.user_id
                                 WHERE r.product_id = $product_id
                                 ORDER BY r.review_date DESC";

                $review_result = mysqli_query($con, $review_query);

                if (mysqli_num_rows($review_result) > 0) {
                    while ($review = mysqli_fetch_assoc($review_result)) {
                        echo '<div class="border p-2 mb-2">
                                <strong>' . htmlspecialchars($review['first_name']) . '</strong>
                                <span class="text-warning">' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '</span>
                                <p>' . htmlspecialchars($review['comment']) . '</p>
                                <small class="text-muted">' . $review['review_date'] . '</small>
                              </div>';
                    }
                } else {
                    echo "<p>No reviews yet.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Review Submission Handler -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            if (!isset($_SESSION['user_id'])) {
                echo "<script>alert('You must be logged in to post a review');</script>";
            } else {
                $user_id = $_SESSION['user_id'];
                $rating = $_POST['rating'];
                $comment = mysqli_real_escape_string($con, $_POST['comment']);
                $review_date = date('Y-m-d H:i:s');


                $insert_review = "INSERT INTO reviews (user_id, product_id, rating, comment, review_date)
                                  VALUES ('$user_id', '$product_id', '$rating', '$comment', '$review_date')";

                if (mysqli_query($con, $insert_review)) {
                    echo "<script>alert('Review posted successfully'); window.location.href='product_details.php?product_id=$product_id';</script>";
                } else {
                    echo "<script>alert('Error posting review');</script>";
                }
            }
        }
        ?>
    </div>

    <!-- Footer -->
    <?php include("./includes/footer.php"); ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
