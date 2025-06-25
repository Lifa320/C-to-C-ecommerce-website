<?php
if (isset($_GET['view_products'])) {
    include('../includes/connect.php');
  

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $get_seller = "SELECT seller_id FROM sellers WHERE user_id = '$user_id'";
    $run_seller = mysqli_query($con, $get_seller);
    $row_seller = mysqli_fetch_assoc($run_seller);
    $seller_id = $row_seller['seller_id'];
$get_products = "SELECT * FROM products WHERE user_id = '$user_id'";

    $result = mysqli_query($con, $get_products);

    echo "<div class='container mt-4'>";
    echo "<h2 class='mb-3'>Your Products</h2>";
    echo "<table class='table table-bordered'>
    <thead class='table-dark'>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Stock</th>
        <th>price</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['description']}</td>
        <td>{$row['stock_quantity']}</td>
        <td>{$row['price']}</td>
        <td><img src='./product_images/{$row['image']}' width='70'></td>
        <td>
            <a class='btn btn-sm btn-primary' href='index.php?edit_product={$row['product_id']}'>Edit</a>
            <a class='btn btn-sm btn-danger' href='index.php?delete_product={$row['product_id']}' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>
        </td>
        </tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

    
}
?>
