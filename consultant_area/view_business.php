<?php

include('../includes/connect.php'); 

if (!isset($_SESSION['user_id'])) {
    header("..users_area/user_login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 
// Fetch approved businesses
$sql = "
    SELECT business_id, user_id, business_name, status, owner_name, 
           submitted_at, business_type, warehouse_address, telephone 
    FROM business_requests 
    WHERE status = 'approved'
    ORDER BY submitted_at DESC
";

$result = $con->query($sql);
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Approved Businesses</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Business Name</th>
                    <th>Owner</th>
                    <th>Type</th>
                    <th>Warehouse Address</th>
                    <th>Telephone</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $count = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['business_name']) ?></td>
                            <td><?= htmlspecialchars($row['owner_name']) ?></td>
                            <td><?= htmlspecialchars($row['business_type']) ?></td>
                            <td><?= htmlspecialchars($row['warehouse_address']) ?></td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No approved businesses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
