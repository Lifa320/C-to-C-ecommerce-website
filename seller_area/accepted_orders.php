<?php

include('../includes/connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Fetch accepted order items for this seller with shipping info and shipping address details
$sql = "
SELECT 
    o.order_id,
    oi.order_item_id,
    oi.product_id,
    p.name AS product_name,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS total_price,
    o.order_date,
    s.shipping_id,
    s.shipping_date,
    s.delivery_date,
    s.shipping_status,
    s.courier_name,
    s.tracking_number,
    s.shipping_address_id,
    a.street,
    a.city,
    a.state,
    a.postal_code,
    a.country
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
LEFT JOIN shipping s ON o.order_id = s.order_id
LEFT JOIN addresses a ON s.shipping_address_id = a.address_id
WHERE p.user_id = ? AND oi.status = 'accepted'
ORDER BY o.order_date DESC
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Accepted Orders</h2>

<table class="table table-striped" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Shipping Address</th>
            <th>Shipping Dates<br>(Shipping / Delivery)</th>
            <th>Shipping Status</th>
            <th>Courier Name</th>
            <th>Tracking Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>R <?= number_format($row['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>
                    <?php if ($row['shipping_address_id']): ?>
                        <?= htmlspecialchars($row['street']) ?><br>
                        <?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['state']) ?><br>
                        <?= htmlspecialchars($row['postal_code']) ?><br>
                        <?= htmlspecialchars($row['country']) ?>
                    <?php else: ?>
                        <em>Not set</em>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['shipping_date'] || $row['delivery_date']): ?>
                        <?= htmlspecialchars($row['shipping_date']) ?><br>
                        <?= htmlspecialchars($row['delivery_date']) ?>
                    <?php else: ?>
                        <em>Not set</em>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['shipping_status'] ?? 'pending') ?></td>
                <td><?= htmlspecialchars($row['courier_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['tracking_number'] ?? '') ?></td>
                <td>
                    <?php if (!$row['shipping_id']): ?>
                        <!-- Form to add shipping info -->
                        <form method="POST" action="index.php?update_shipping">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <input type="datetime-local" name="shipping_date" required><br>
                            <input type="datetime-local" name="delivery_date" required><br>
                            <select name="shipping_status" required>
                                <option value="pending" selected>Pending</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select><br>
                            <input type="text" name="courier_name" placeholder="Courier Name" required><br>
                            <input type="text" name="tracking_number" placeholder="Tracking Number" required><br>
                            <button type="submit">Save Shipping</button>
                        </form>
                    <?php else: ?>
                        <em>Shipping complete</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
