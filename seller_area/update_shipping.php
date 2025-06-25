<?php
include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $order_id = intval($_POST['order_id']);
    $shipping_date = $_POST['shipping_date'] ?? null;
    $delivery_date = $_POST['delivery_date'] ?? null;
    $shipping_status = $_POST['shipping_status'] ?? 'pending';
    $courier_name = trim($_POST['courier_name'] ?? '');
    $tracking_number = trim($_POST['tracking_number'] ?? '');

    // Basic validation
    if (!$order_id || !$shipping_date || !$delivery_date || !$courier_name || !$tracking_number) {
        die("All fields are required.");
    }

    // Verify the order belongs to this seller 
    $stmt = $con->prepare("
        SELECT 1
        FROM Orders o
        JOIN Order_Items oi ON o.order_id = oi.order_id
        JOIN Products p ON oi.product_id = p.product_id
        WHERE o.order_id = ? AND p.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $order_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid order or you don't have permission.");
    }

    // Check if shipping record exists (unique order_id)
    $stmt = $con->prepare("SELECT shipping_id FROM Shipping WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if ($existing) {
        // Update existing shipping
        $stmt = $con->prepare("
            UPDATE Shipping SET
                shipping_date = ?,
                delivery_date = ?,
                shipping_status = ?,
                courier_name = ?,
                tracking_number = ?
            WHERE order_id = ?
        ");
        $stmt->bind_param("sssssi", $shipping_date, $delivery_date, $shipping_status, $courier_name, $tracking_number, $order_id);
        $stmt->execute();
    } else {
        
        $shipping_address_id = null;

        $stmt = $con->prepare("
            INSERT INTO Shipping 
            (order_id, shipping_address_id, shipping_date, delivery_date, shipping_status, courier_name, tracking_number)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssss", $order_id, $shipping_address_id, $shipping_date, $delivery_date, $shipping_status, $courier_name, $tracking_number);
        $stmt->execute();
    }

    // Redirect back to accepted_orders.php (or another page)
    
    header("Location: accepted_orders.php");
    exit();
} else {
    die("Invalid request.");
}