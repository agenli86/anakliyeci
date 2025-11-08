<?php
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdmin();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Adana Nakliye Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.mce.com/1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 20px;
            background: #1a252f;
            border-bottom: 1px solid #34495e;
        }
        .sidebar-header h2 {
            font-size: 18px;
            color: #FFC107;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #34495e;
            border-left-color: #D50000;
        }
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
        }
        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 20px;
        }
        .top-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .top-bar h1 {
            color: #2c3e50;
            font-size: 24px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info span {
            color: #666;
        }
        .btn-logout {
            padding: 8px 15px;
            background: #D50000;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: #B71C1C;
        }
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .data-table th {
            background: #2c3e50;
            color: white;
            font-weight: 600;
        }
        .data-table tr:hover {
            background: #f5f5f5;
        }
        .data-table img {
            max-width: 80px;
            border-radius: 5px;
        }
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="password"],
        .form-group input[type="file"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        /* Button Styles */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            font-size: 14px;
        }
        .btn-primary {
            background: #D50000;
            color: white;
        }
        .btn-primary:hover {
            background: #B71C1C;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .dashboard-card-icon {
            font-size: 40px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
        .dashboard-card-content h3 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .dashboard-card-content p {
            color: #666;
            font-size: 14px;
        }
        .card-red .dashboard-card-icon {
            background: #ffebee;
            color: #D50000;
        }
        .card-blue .dashboard-card-icon {
            background: #e3f2fd;
            color: #2196F3;
        }
        .card-green .dashboard-card-icon {
            background: #e8f5e9;
            color: #4CAF50;
        }
        .card-yellow .dashboard-card-icon {
            background: #fff3e0;
            color: #FF9800;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-truck"></i> Adana Nakliye</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Ana Sayfa
                </a></li>
                <li><a href="sliders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'sliders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Slider Yönetimi
                </a></li>
                <li><a href="services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' || basename($_SERVER['PHP_SELF']) == 'service-edit.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cogs"></i> Hizmetler
                </a></li>
                <li><a href="blog.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' || basename($_SERVER['PHP_SELF']) == 'blog-edit.php' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marker-alt"></i> Bölgeler/Blog
                </a></li>
                <li><a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
                    <i class="fas fa-image"></i> Galeri
                </a></li>
                <li><a href="tabs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tabs.php' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i> Tab İçerikleri
                </a></li>
                <li><a href="sss.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'sss.php' ? 'active' : ''; ?>">
                    <i class="fas fa-question-circle"></i> SSS
                </a></li>
                <li><a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Site Ayarları
                </a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></h1>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> <?php echo $_SESSION['admin_fullname']; ?></span>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
                </div>
            </div>

            <?php if ($success = getSuccess()): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error = getError()): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
