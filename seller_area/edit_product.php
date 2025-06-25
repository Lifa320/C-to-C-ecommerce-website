<?php
include('../includes/connect.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $get_product = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $get_product);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $description = $_POST['description'];
    $stock_quantity = $_POST['stock_quantity'];
    $image = $_FILES['image']['name'];
    $temp_image = $_FILES['image']['tmp_name'];

    if (!empty($image)) {
        move_uploaded_file($temp_image, "./product_images/$image");
        $update_query = "UPDATE products SET description='$description', stock_quantity='$stock_quantity', image='$image' WHERE product_id='$product_id'";
    } else {
        $update_query = "UPDATE products SET description='$description', stock_quantity='$stock_quantity' WHERE product_id='$product_id'";
    }

    $result = mysqli_query($con, $update_query);

    if ($result) {
        echo "<script>alert('Product updated successfully')</script>";
        echo "<script>window.location.href='manage_products.php'</script>";
    }
}
?>

<!-- Basic form -->
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
    <label>Description:</label>
    <textarea name="description"><?php echo $row['description']; ?></textarea><br>
    <label>Stock Quantity:</label>
    <input type="number" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>"><br>
    <label>Change Image:</label>
    <input type="file" name="image"><br>
    <img src="./product_images/<?php echo $row['image']; ?>" width="100"><br><br>
    <input type="submit" name="update_product" value="Update Product">
</form>
