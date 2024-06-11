<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-db-connection.php");
include($_SERVER['DOCUMENT_ROOT'] . "/includes/inc-functions.php");

$user_id = $_SESSION['user_id'];
$user_info = DB::getInstance()->selectOne("SELECT name, email, company_name, phone, address, job_title, created_at, role FROM users WHERE id = :user_id", ['user_id' => $user_id]);
$avatar_info = DB::getInstance()->selectOne("SELECT avatar_path FROM user_avatars WHERE user_id = :user_id", ['user_id' => $user_id]);
$avatar_path = $avatar_info ? '/app/avatar/' . $avatar_info['avatar_path'] : '/app/avatar/avatar-standard-male.png';

$current_page = basename($_SERVER['PHP_SELF'], ".php");
$page_titles = [
    'index' => 'Dashboard',
    'profile' => 'Profile',
    'contacts' => 'Contacts',
    'sales' => 'Sales',
    'admin-users' => 'Manage Users',
    'admin-settings' => 'Settings',
    'admin-reports' => 'Reports',
];

$page_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Page';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SyncFàs CRM | <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= fullUrl(); ?>app/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="<?= fullUrl(); ?>app/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a href="index.php" class="nav-link">Home</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($user_info['role']) && $user_info['role'] === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin Panel
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="admin-users.php">Manage Users</a></li>
                                <li><a class="dropdown-item" href="admin-settings.php">Settings</a></li>
                                <li><a class="dropdown-item" href="admin-reports.php">Reports</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="#" data-lte-toggle="fullscreen"><i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i><i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i></a></li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="<?= htmlspecialchars($avatar_path); ?>" class="user-image rounded-circle shadow" alt="User Image">
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($user_info['name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="<?= htmlspecialchars($avatar_path); ?>" class="rounded-circle shadow" alt="User Image">
                                <p><?php echo htmlspecialchars($user_info['name']); ?> - <?php echo htmlspecialchars($user_info['job_title']); ?><small>Member since <?php echo date('M. Y', strtotime($user_info['created_at'])); ?></small></p>
                            </li>
                            <li class="user-footer">
                                <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                                <a href="logout.php" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="<?= fullUrl(); ?>" class="brand-link">
                    <i class="fas fa-chart-line" style="margin-right: 10px; color: #26A69A;"></i>
                    <span class="brand-text fw-light">SyncFàs CRM</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link <?php echo $current_page === 'profile' ? 'active' : ''; ?>">
                                <i class="nav-icon bi bi-person"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="contacts.php" class="nav-link <?php echo $current_page === 'contacts' ? 'active' : ''; ?>">
                                <i class="nav-icon bi bi-person-lines-fill"></i>
                                <p>Contacts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sales.php" class="nav-link <?php echo $current_page === 'sales' ? 'active' : ''; ?>">
                                <i class="nav-icon bi bi-bar-chart-line"></i>
                                <p>Sales</p>
                            </a>
                        </li>
                        <!-- Additional nav items here -->
                    </ul>
                </nav>
            </div>
        </aside>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= $page_title; ?></h3>
                        </div>