<?php


// Fetch user info
$userRes = mysqli_query($con, "SELECT email, first_name, last_name, user_phone, user_image FROM users WHERE user_id = $user_id");
$imagePath = !empty($user['user_image']) ? './profile/' . $user['user_image'] : 'https://via.placeholder.com/150';

$user = mysqli_fetch_assoc($userRes);

// Fetch addresses
$addrRes = mysqli_query($con, "SELECT street, city, state, postal_code, country FROM addresses WHERE user_id = $user_id");
$addresses = [];
while ($addr = mysqli_fetch_assoc($addrRes)) {
    $addresses[] = $addr;
}
?>

<link rel="stylesheet" href="../style.css">

<div class="justify-content:center p-4">
  <h2 id="account-overview">Consultant Admin Dashboard</h2>

  <ul class="nav nav-tabs" id="accountTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
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
     style="width:150px;height:150px;border-radius:50%;">
        <h5>Admin Overview</h5>
        <p><strong>Admin ID:</strong> <?= htmlspecialchars($user_id) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
        <button class="btn btn-danger">Delete Account</button>
      </div>
    </div>

    <!-- Address Tab -->
    <div class="tab-pane fade" id="address" role="tabpanel">
      <div class="mt-4">
        <h5>Registered Addresses</h5>
        <table class="table table-borderless">
          <tbody>
            <?php if (count($addresses) > 0): ?>
              <?php foreach ($addresses as $addr): ?>
                <tr>
                  <th scope="row"><?= htmlspecialchars($addr['street'] . ', ' . $addr['city']) ?></th>
                  <td><?= htmlspecialchars($addr['state'] . ' ' . $addr['postal_code'] . ', ' . $addr['country']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="2">No address found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Profile Tab -->
    <div class="tab-pane fade" id="profile" role="tabpanel">
      <div class="mt-4">
        <h5>Contact Information</h5>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
        <p><strong>Telephone:</strong> <?= htmlspecialchars($user['user_phone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <button class="btn btn-primary me-2">Edit Profile</button>
        <button class="btn btn-warning">Reset Password</button>
      </div>
    </div>
  </div>
</div>
