<?php

include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_item_id']) && isset($_POST['new_status'])) {
    $order_item_id = intval($_POST['order_item_id']);
    $new_status = $_POST['new_status'];

    // Only update if the product belongs to this seller
    $stmt = $con->prepare("UPDATE order_items 
                           SET status = ? 
                           WHERE order_item_id = ? 
                           AND product_id IN (SELECT product_id FROM products WHERE user_id = ?)");
    $stmt->bind_param("sii", $new_status, $order_item_id, $seller_id);
    $stmt->execute();
}

// Fetch order items for this seller
$stmt = $con->prepare("SELECT 
                            o.order_id, 
                            oi.order_item_id,
                            oi.product_id, 
                            oi.quantity, 
                            o.order_date, 
                            oi.status
                        FROM orders o
                        JOIN order_items oi ON o.order_id = oi.order_id
                        JOIN products p ON oi.product_id = p.product_id
                        WHERE p.user_id = ?
                        ORDER BY o.order_date DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

// Display
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Qty</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['product_id']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td id="status-<?= $row['order_item_id'] ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_item_id" value="<?= $row['order_item_id'] ?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <option disabled selected>Change status</option>
                            <option value="accepted">Accept</option>
                            <option value="declined">Decline</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
