<?php
include('../includes/connect.php');

// Ensure seller_id is provided
if (!isset($_GET['view_seller_products'])) {
    echo "<p>No seller selected.</p>";
    exit();
}

$seller_id = intval($_GET['view_seller_products']);
?>
<!-- Container for all seller products -->
<div class="container mt-4">
    <h3 class="text-center mb-4">Seller's Products</h3>
    <?php
    $product_query = "SELECT * FROM products WHERE user_id = $seller_id";  
    $product_result = mysqli_query($con, $product_query);

    if (mysqli_num_rows($product_result) > 0) {
        while ($row = mysqli_fetch_assoc($product_result)) {
            $product_id = $row['product_id'];
            echo '<div class="row mb-5 border-bottom pb-4">
                    <div class="col-md-6">
                        <img src="../seller_area/product_images/' . htmlspecialchars($row['image']) . '" 
                             class="img-fluid small-image" alt="' . htmlspecialchars($row['name']) . '">
                    </div>
                    <div class="col-md-6">
                        <h4>' . htmlspecialchars($row['name']) . '</h4>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                        <h5 class="text-success">R' . number_format($row['price'], 2) . '</h5>
                     
                    </div>';
            // Fetch and display reviews
            $review_query = "
                SELECT r.*, u.first_name 
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                WHERE r.product_id = $product_id
                ORDER BY r.review_date DESC
            ";
            $review_result = mysqli_query($con, $review_query);
            echo '<div class="col-md-12 mt-3">
                    <h5>Customer Reviews</h5>';

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
            echo '</div></div>';
        }
    } else {
        echo "<p>No products found for this seller.</p>";
    }
    ?>
</div>
