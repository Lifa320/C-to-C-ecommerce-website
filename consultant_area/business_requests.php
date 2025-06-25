<?php
include('../includes/connect.php');

// Handle Approve/Reject Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_id = $_POST['business_id'];
    
    if (isset($_POST['approve'])) {
        $status = 'approved';
    } elseif (isset($_POST['reject'])) {
        $status = 'rejected';
    }

    $update_query = "UPDATE business_requests SET status = ? WHERE business_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("si", $status, $business_id);
    $stmt->execute();
}

// Fetch Business Requests with User Info
$query = "
    SELECT br.business_id, br.business_name, br.owner_name, br.business_type, br.warehouse_address, br.telephone, br.status, br.submitted_at,
           u.email
    FROM business_requests br
    JOIN users u ON br.user_id = u.user_id
    ORDER BY br.submitted_at DESC
";
$result = mysqli_query($con, $query);
?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4">Business Registration Requests</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>User Email</th>
                <th>Business Name</th>
                <th>Owner Name</th>
                <th>Business Type</th>
                <th>Warehouse Address</th>
                <th>Telephone</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $count++;
                $badge = match(strtolower($row['status'])) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'secondary',
                };

                echo "<tr>
                        <td>{$count}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['business_name']}</td>
                        <td>{$row['owner_name']}</td>
                        <td>{$row['business_type']}</td>
                        <td>{$row['warehouse_address']}</td>
                        <td>{$row['telephone']}</td>
                        <td><span class='badge bg-{$badge}'>" . ucfirst($row['status']) . "</span></td>
                        <td>{$row['submitted_at']}</td>
                        <td>
                            <form method='POST' action=''>
                                <input type='hidden' name='business_id' value='{$row['business_id']}'>
                                <button name='approve' class='btn btn-success btn-sm me-1'>Approve</button>
                                <button name='reject' class='btn btn-danger btn-sm'>Reject</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
