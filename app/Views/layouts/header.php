<?php 
    // KHAI BÁO ĐƯỜNG DẪN GỐC - ĐÂY LÀ CHÌA KHÓA FIX LỖI VỠ GIAO DIỆN
    $baseUrl = 'http://localhost/ThucTap'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Logistics Tân Cảng</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS (Đã sửa đường dẫn) -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/public/css/style.css">
</head>
<body>

<div class="wrapper">
    <!-- SIDEBAR MỚI (SÁNG MÀU) -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-ship me-2"></i> LOGIS<span class="text-info">PORT</span>
        </div>
        <div class="sidebar-menu">
            <p class="menu-label">BẢNG ĐIỀU KHIỂN</p>
            <a href="#" class="active"><i class="fas fa-chart-pie"></i> Tổng quan (Dashboard)</a>
            
            <p class="menu-label mt-3">NGHIỆP VỤ LOGISTICS</p>
            <a href="#"><i class="fas fa-clipboard-check"></i> Tiếp nhận lệnh / Đơn hàng</a>
            <a href="#"><i class="fas fa-box"></i> Tra cứu Container</a>
            
            <p class="menu-label mt-3">HỆ THỐNG</p>
            <a href="#quanTriMenu" data-bs-toggle="collapse"><i class="fas fa-database"></i> Quản lý Danh mục <i class="fas fa-angle-down float-end mt-1"></i></a>
            <div class="collapse" id="quanTriMenu">
                <a href="#" class="ps-4"><i class="fas fa-warehouse"></i> Danh mục Depot</a>
                <a href="#" class="ps-4"><i class="fas fa-anchor"></i> Hãng tàu & Biểu phí</a>
            </div>
            <a href="#"><i class="fas fa-users-cog"></i> Quản trị Tài khoản</a>
            <a href="#"><i class="fas fa-file-excel"></i> Báo cáo & Thống kê</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR MỚI (XANH ĐẠI DƯƠNG) -->
        <header class="topbar">
            <div class="toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </div>
            <div class="topbar-right">
                <div class="icon-wrapper">
                    <i class="fas fa-bell"></i>
                    <span class="badge-noti">3</span>
                </div>
                <div class="user-profile dropdown">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=fff&color=0284c7" alt="Avatar" class="rounded-circle me-2" width="35">
                    <span class="dropdown-toggle" data-bs-toggle="dropdown">Quản trị viên</span>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="content-wrapper">