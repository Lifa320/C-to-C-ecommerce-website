<?php
include(__DIR__ . '/../includes/connect.php');

//getting products
function getproducts(){
    global $con;

    //isset or not
    if(!isset($_GET['category'])){
        if(!isset($_GET['brand'])){

    
    $select_query="Select*from products order by rand() LIMIT 0,4";
    $result_query=mysqli_query($con,$select_query);
    while($row=mysqli_fetch_assoc($result_query)){
      $product_id=$row['product_id'];
      $name=$row['name'];
      $description=$row['description'];
      $price=$row['price'];
      $image=$row['image'];
        $image_path = "../seller_area/product_images/$image";
        // Fetch average rating and review count for this product
    $rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count 
                   FROM reviews 
                   WHERE product_id = $product_id";
    $rating_result = mysqli_query($con, $rating_sql);
    $rating_data = mysqli_fetch_assoc($rating_result);
    
    $avg_rating = round($rating_data['avg_rating'] ?? 0);
    $review_count = intval($rating_data['review_count']);
     // Build star display
    $stars = str_repeat('★', $avg_rating) . str_repeat('☆', 5 - $avg_rating);
      echo " <div class='col-md-4 mb-2 '>
    <div class='card' >
<img src='$image_path' class='card-img-top' alt='$image'>
<div class='card-body '>
<h5 class='card-title'>$name</h5>
<p class='card-text'>$description</p>
</div>
<ul class='list-group list-group-flush'>
<li class='list-group-item'>An item</li>
<li class='list-group-item'>Price: R$price</li>
 <li class='list-group-item'>
                    Rating: <span class='text-warning'>$stars</span>
                    <small class='text-muted'>($review_count review" . ($review_count != 1 ? "s" : "") . ")</small>
                </li>
</ul>
<div class='card-body'>
<a href='../customer_area/index.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
<a href='../product_details.php?product_id=$product_id' class='btn btn-secondary'>More info</a>
</div>
</div>
</div>";

    }
}
}
}

// getting all products
function get_all_products(){
    global $con;

    //isset or not
    if(!isset($_GET['category'])){
        if(!isset($_GET['brand'])){

    
    $select_query="Select*from products order by rand()";
    $result_query=mysqli_query($con,$select_query);
      while($row=mysqli_fetch_assoc($result_query)){
      $product_id=$row['product_id'];
      $name=$row['name'];
      $description=$row['description'];
      $price=$row['price'];
   
      $image=$row['image'];
        $image_path = "../seller_area/product_images/$image";
        // Fetch average rating and review count for this product
    $rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count 
                   FROM reviews 
                   WHERE product_id = $product_id";
    $rating_result = mysqli_query($con, $rating_sql);
    $rating_data = mysqli_fetch_assoc($rating_result);
    
    $avg_rating = round($rating_data['avg_rating'] ?? 0);
    $review_count = intval($rating_data['review_count']);
     // Build star display
    $stars = str_repeat('★', $avg_rating) . str_repeat('☆', 5 - $avg_rating);
      echo " <div class='col-md-4 mb-2 '>
    <div class='card' >
<img src='$image_path' class='card-img-top' alt='$image'>
<div class='card-body '>
<h5 class='card-title'>$name</h5>
<p class='card-text'>$description</p>
</div>
<ul class='list-group list-group-flush'>
<li class='list-group-item'>An item</li>
<li class='list-group-item'>Price: R$price</li>
 <li class='list-group-item'>
                    Rating: <span class='text-warning'>$stars</span>
                    <small class='text-muted'>($review_count review" . ($review_count != 1 ? "s" : "") . ")</small>
                </li>
</ul>
<div class='card-body'>
<a href='../customer_area/index.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
<a href='../product_details.php?product_id=$product_id' class='btn btn-secondary'>More info</a>
</div>
</div>
</div>";

    }
}
}

}
//getting unique categories
function get_specific_cate(){
    global $con;
    //isset or not
    if(isset($_GET['category'])){
        $category_id=$_GET['category'];
               
    $select_query="Select*from products where category_id=$category_id";
    $result_query=mysqli_query($con,$select_query);
    $num_of_rows=mysqli_num_rows($result_query);
    if($num_of_rows==0){
        echo"<h2 class='text-center text-danger'> No available products for this category</h2>";
    }
    
    while($row=mysqli_fetch_assoc($result_query)){
      $product_id=$row['product_id'];
      $name=$row['name'];
      $description=$row['description'];
      $price=$row['price'];
      $category_id=$row['category_id'];
      $image=$row['image'];
       $image_path = "../seller_area/product_images/$image";
        // Fetch average rating and review count for this product
    $rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count 
                   FROM reviews 
                   WHERE product_id = $product_id";
    $rating_result = mysqli_query($con, $rating_sql);
    $rating_data = mysqli_fetch_assoc($rating_result);
    
    $avg_rating = round($rating_data['avg_rating'] ?? 0);
    $review_count = intval($rating_data['review_count']);

    // Build star display
    $stars = str_repeat('★', $avg_rating) . str_repeat('☆', 5 - $avg_rating);
      echo " <div class='col-md-4 mb-2 '>
    <div class='card' >
<img src='$image_path' class='card-img-top' alt='$image'>
<div class='card-body '>
<h5 class='card-title'>$name</h5>
<p class='card-text'>$description</p>
</div>
<ul class='list-group list-group-flush'>
<li class='list-group-item'>An item</li>
<li class='list-group-item'>Price: R$price</li>
 <li class='list-group-item'>
                    Rating: <span class='text-warning'>$stars</span>
                    <small class='text-muted'>($review_count review" . ($review_count != 1 ? "s" : "") . ")</small>
                </li>
</ul>
<div class='card-body'>
<a href='../customer_area/index.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
<a href='../product_details.php?product_id=$product_id' class='btn btn-secondary'>More info</a>
</div>
</div>
</div>";

    }
    }
}

//displaying categories in sidenav
function getcategories(){
    global $con;
    $select_categories="Select * from categories";
    $result_categories=mysqli_query($con,$select_categories);

  while( $row_data=mysqli_fetch_assoc($result_categories)){
    $category_id=$row_data['category_id'];
    $name=$row_data['name'];
    echo" <li class='nav-item bg-primary'>
        <a href='index.php?category=$category_id' class='nav-link'>$name</a>
    </li>";
  }
}


//searching products

function search_product(){
    global $con;
    if(isset($_GET['search_data_product'])){
   
        $search_data_value=$_GET['search_data'];
    $search_query="Select*from products where keyword like '%$search_data_value%'";
    $result_query=mysqli_query($con,$search_query);
    $num_of_rows=mysqli_num_rows($result_query);
    if($num_of_rows==0){
        echo"<h2 class='text-center text-danger'> No available products found</h2>";
    }
    while($row=mysqli_fetch_assoc($result_query)){
      $product_id=$row['product_id'];
      $name=$row['name'];
      $description=$row['description'];
      $price=$row['price'];
      $category_id=$row['category_id'];
      $image=$row['image'];
        $image_path = "./seller_area/product_images/$image";
        // Fetch average rating and review count for this product
    $rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count 
                   FROM reviews 
                   WHERE product_id = $product_id";
    $rating_result = mysqli_query($con, $rating_sql);
    $rating_data = mysqli_fetch_assoc($rating_result);
    
    $avg_rating = round($rating_data['avg_rating'] ?? 0);
    $review_count = intval($rating_data['review_count']);
     // Build star display
    $stars = str_repeat('★', $avg_rating) . str_repeat('☆', 5 - $avg_rating);
      echo " <div class='col-md-4 mb-2 '>
    <div class='card' >
<img src='$image_path' class='card-img-top' alt='$image'>
<div class='card-body '>
<h5 class='card-title'>$name</h5>
<p class='card-text'>description</p>
</div>
<ul class='list-group list-group-flush'>
<li class='list-group-item'>An item</li>
<li class='list-group-item'>Price: R$price</li>
 <li class='list-group-item'>
                    Rating: <span class='text-warning'>$stars</span>
                    <small class='text-muted'>($review_count review" . ($review_count != 1 ? "s" : "") . ")</small>
                </li></ul>
<div class='card-body'>
<a href='./index.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
<a href='../product_details.php?product_id=$product_id' class='btn btn-secondary'>More info</a>
</div>
</div>
</div>";

    }
}

}
// view details funtion
function view_details() {
    global $con;
    
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    
       
    
        // REVIEW SUBMISSION
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            if (!isset($_SESSION['user_id'])) {
                echo "<script>alert('You must be logged in to post a review');</script>";
            } else {
                $user_id = $_SESSION['user_id'];
                $rating = $_POST['rating'];
                $comment = mysqli_real_escape_string($con, $_POST['comment']);
                $review_date = date('Y-m-d');
    
                $insert_review = "INSERT INTO reviews (user_id, product_id, rating, comment, review_date)
                                VALUES ('$user_id', '$product_id', '$rating', '$comment', '$review_date')";
    
                $result_review = mysqli_query($con, $insert_review);
    
                if ($result_review) {
                    echo "<script>alert('Review posted successfully');</script>";
                } else {
                    echo "<script>alert('Error posting review');</script>";
                }
            }
        }
    
        // SHOW REVIEW FORM
        echo '<div class="card mt-3">
                <div class="card-header bg-secondary text-white">Leave a Review</div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" name="rating" id="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Good</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Poor</option>
                                <option value="1">1 - Terrible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>';
    
        // DISPLAY EXISTING REVIEWS
        $get_reviews = "SELECT r.rating, r.comment, r.review_date, u.first_name
                        FROM reviews r
                        JOIN users u ON r.user_id = u.user_id
                        WHERE r.product_id = '$product_id'
                        ORDER BY r.review_date DESC";
        $result_reviews = mysqli_query($con, $get_reviews);
    
        if (mysqli_num_rows($result_reviews) > 0) {
            echo '<div class="mt-4"><h5 class="text-secondary">Customer Reviews</h5>';
            while ($row = mysqli_fetch_assoc($result_reviews)) {
                echo '<div class="border rounded p-3 mb-2 bg-light">
                        <strong>' . htmlspecialchars($row['first_name']) . '</strong> - 
                        <span class="text-warning">' . str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) . '</span><br>
                        <small class="text-muted">' . $row['review_date'] . '</small>
                        <p class="mb-0">' . htmlspecialchars($row['comment']) . '</p>
                    </div>';
            }
            echo '</div>';
        } else {
            echo '<p class="mt-3 text-muted">No reviews yet. Be the first to review this product.</p>';
        }
    }
    
}


    //cart function
    function cart() {
        if (isset($_GET['add_to_cart'])) {
            global $con;
    
            if (!isset($_SESSION['user_id'])) {
                echo "<script>alert('Please log in to add items to the cart.')</script>";
                echo "<script>window.open('..customer_area/login.php','_self')</script>";
                return;
            }
    
            $user_id = $_SESSION['user_id'];
            $product_id = intval($_GET['add_to_cart']); // Sanitize
    
            // Step 1: Check if cart exists for this user
            $check_cart_query = "SELECT cart_id FROM carts WHERE user_id = '$user_id'";
            $check_cart_result = mysqli_query($con, $check_cart_query);
    
            if ($check_cart_result && mysqli_num_rows($check_cart_result) > 0) {
                $cart_row = mysqli_fetch_assoc($check_cart_result);
                $cart_id = $cart_row['cart_id'];
            } else {
                // Step 2: Create a new cart for the user
                $create_cart_query = "INSERT INTO carts (user_id, created_at, updated_at) VALUES ('$user_id', NOW(), NOW())";
                $create_cart_result = mysqli_query($con, $create_cart_query);
    
                if (!$create_cart_result) {
                    echo "<script>alert('Failed to create a cart.')</script>";
                    return;
                }
    
                $cart_id = mysqli_insert_id($con); // Get the new cart ID
            }
    
            // Step 3: Check if product is already in the cart
            $check_item_query = "SELECT * FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
            $check_item_result = mysqli_query($con, $check_item_query);
    
            if (mysqli_num_rows($check_item_result) > 0) {
                echo "<script>alert('This item is already in your cart.')</script>";
            } else {
                // Step 4: Add product to cart
                $add_item_query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', 1)";
                $add_item_result = mysqli_query($con, $add_item_query);
    
                if ($add_item_result) {
                    echo "<script>alert('Item added to cart.')</script>";
                } else {
                    echo "<script>alert('Failed to add item to cart.')</script>";
                }
            }
    
            echo "<script>window.open('../customer_area/index.php','_self')</script>";
        }
    }
    
    
    //function to get cart item number
    function total_cart_price() {
        global $con;
    
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo "User not logged in.";
            return;
        }
    
        $user_id = $_SESSION['user_id'];
        $total = 0;
    
        // Retrieve the cart ID associated with the user
        $cart_query = "SELECT cart_id FROM carts WHERE user_id = ?";
        $stmt = $con->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if a cart ID was returned
        if ($row = $result->fetch_assoc()) {
            $cart_id = $row['cart_id'];
    
            // Retrieve cart items and calculate the total price
            $cart_items_query = "
                SELECT ci.quantity, p.price
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.product_id
                WHERE ci.cart_id = ?
            ";
            $stmt = $con->prepare($cart_items_query);
            $stmt->bind_param("i", $cart_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
            }
        }
    
        $stmt->close();
    
        // Output the total price
        echo $total;
    }

    function cart_item() {
        global $con;
    
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo 0;
            return;
        }
    
        $user_id = $_SESSION['user_id'];
    
        // Retrieve the cart ID associated with the user
        $cart_query = "SELECT cart_id FROM carts WHERE user_id = ?";
        $stmt = $con->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $cart_id = $row['cart_id'];
            $stmt->close();
    
            // Count the number of items in the cart
            $count_query = "SELECT COUNT(*) AS item_count FROM cart_items WHERE cart_id = ?";
            $stmt = $con->prepare($count_query);
            $stmt->bind_param("i", $cart_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($row = $result->fetch_assoc()) {
                $item_count = $row['item_count'];
                echo $item_count;
            } else {
                echo 0;
            }
    
            $stmt->close();
        } else {
            $stmt->close();
            echo 0;
        }
    }
    
    
        
?>