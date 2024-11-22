
<?php 

// Check if user is logged in, otherwise redirect to login page
if (!$session->isUserLoggedIn()) {
    header("Location: admin-login.php");
    exit;
}

// Check if logout is requested


// Retrieve logged-in user details
$user_id = $_SESSION['user_id'] ?? null;
$user = find_by_id('tbl_user', $user_id); // Assumes `find_by_id` fetches user data by ID
?>
<div class="header">

    <!-- Logo -->
    <div class="header-left">
        <a href="admin-dashboard.php" class="logo">
            <img src="assets/img/fzlogo.png" width="90" height="auto" alt="">
        </a>
        <a href="admin-dashboard.php" class="logo2">
            <img src="assets/img/fzlogo.png" width="100" height="100" alt="">
        </a>
    </div>
    <!-- /Logo -->

    <a id="toggle_btn" href="javascript:void(0);">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Title -->
    <div class="page-title-box">
        <h3>Fit Zone</h3>
    </div>
    <!-- /Header Title -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>

    <!-- Header Menu -->
    <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img src="assets/img/profiles/avatar-21.jpg" alt="">
                    <span class="status online"></span>
                </span>
                <span>
                    <?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?>
                </span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="profile.php">My Profile</a>
                <a class="dropdown-item" href="settings.php">Settings</a>
                <a class="dropdown-item" href="?logout=true">Logout</a> <!-- Logout link triggers the logout process -->
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="settings.php">Settings</a>
            <a class="dropdown-item" href="?logout=true">Logout</a> <!-- Logout link triggers the logout process -->
        </div>
    </div>
    <!-- /Mobile Menu -->

</div>
<!-- /Header -->
