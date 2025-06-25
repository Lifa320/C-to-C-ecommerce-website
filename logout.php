<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login or home page
header("Location: /users_area/user_login.php");

exit();
?>
