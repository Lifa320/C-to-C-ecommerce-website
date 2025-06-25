<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../includes/connect.php');



if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch seller info
$sellerRes = mysqli_query($con, "SELECT seller_id FROM sellers WHERE user_id = $user_id");
if (!$sellerRes || mysqli_num_rows($sellerRes) === 0) {
    echo "<script>alert('Seller record not found. Please contact support.');window.location.href='../index.php';</script>";
    exit();
}
$seller = mysqli_fetch_assoc($sellerRes);
$seller_id = intval($seller['seller_id']);

// Fetch latest business request
$brRes = mysqli_query($con, 
    "SELECT business_id, business_name, status, owner_name, business_type, warehouse_address, telephone, submitted_at
     FROM business_requests
     WHERE user_id = $user_id
     ORDER BY submitted_at DESC
     LIMIT 1"
);
$br = mysqli_fetch_assoc($brRes);

// Fetch user info
$userRes = mysqli_query($con, "SELECT email, first_name, last_name, user_phone, user_image FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($userRes);

// Fetch addresses
$addrRes = mysqli_query($con, "SELECT street, city, state, postal_code, country FROM addresses WHERE user_id = $user_id");
$imagePath = !empty($user['user_image']) ? './profile/' . $user['user_image'] : 'https://via.placeholder.com/150';

$addresses = [];
while ($addr = mysqli_fetch_assoc($addrRes)) {
    $addresses[] = $addr;
}
?>

<link rel="stylesheet" href="../style.css">

<div class="justify-content:center p-4">
  <h2 id="account-overview">Account Overview</h2>

  <ul class="nav nav-tabs" id="accountTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab">Business Information</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">Address</button>
    </li>
  
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile</button>
    </li>
  </ul>

  <div class="tab-content" id="accountTabContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
      <div class="mt-4 text-center">
<img src="./profile/<?= htmlspecialchars($user['user_image'] ?: 'default.jpg') ?>" 
     alt="Profile Pictureee" 
     class="profile-pic mb-3" 
     style="width:150px;height:150px;border-radius:50%;">        <h5>Registration Information</h5>
        <p><strong>Seller ID:</strong> <?= htmlspecialchars($seller_id) ?></p>
        <p><strong>Status:</strong> 
          <span class="badge bg-<?= $br['status'] === 'approved' ? 'success' : ($br['status'] === 'pending' ? 'warning' : 'danger') ?>">
            <?= ucfirst(htmlspecialchars($br['status'])) ?>
          </span>
        </p>
        <button class="btn btn-primary me-2">Update Business account details</button>
        <button class="btn btn-danger">Delete Account</button>
      </div>
    </div>

    <!-- Business Information Tab -->
    <div class="tab-pane fade" id="business" role="tabpanel">
      <div class="mt-4">
        <h5>Business Information</h5>
        <p><strong>Business Name:</strong> <?= htmlspecialchars($br['business_name']) ?></p>
        <p><strong>Business Type:</strong> <?= htmlspecialchars($br['business_type']) ?></p>
        <p><strong>Business ID:</strong> <?= htmlspecialchars($br['business_id']) ?></p>
        <p><strong>Owner Name:</strong> <?= htmlspecialchars($br['owner_name']) ?></p>
      </div>
    </div>

    <!-- Address Tab -->
    <div class="tab-pane fade" id="address" role="tabpanel">
      <div class="mt-4">
        <h5>Addresses</h5>
        <table class="table table-borderless">
          <tbody>
            <?php foreach ($addresses as $addr): ?>
              <tr>
                <th scope="row"><?= htmlspecialchars($addr['street'] . ', ' . $addr['city']) ?></th>
                <td><?= htmlspecialchars($addr['state'] . ' ' . $addr['postal_code'] . ', ' . $addr['country']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <h5>Warehouse Address</h5>
        <p><?= htmlspecialchars($br['warehouse_address']) ?></p>
      </div>
    </div>

 

    <!-- Profile Tab -->
    <div class="tab-pane fade" id="profile" role="tabpanel">
      <div class="mt-4">
        <h5>Contact Information</h5>
        <p><strong>Owner Name:</strong> <?= htmlspecialchars($br['owner_name']) ?></p>
        <p><strong>Telephone:</strong> <?= htmlspecialchars($br['telephone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <button class="btn btn-primary me-2">Edit Profile</button>
        <button class="btn btn-warning">Reset Password</button>
      </div>
    </div>
  </div>
</div>
