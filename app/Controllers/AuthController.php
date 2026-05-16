<?php

class AuthController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // Hiển thị form đăng nhập
    public function showLogin() {
        require_once 'app/Views/auth/login.php';
    }

    // Xử lý khi user bấm nút Đăng nhập
    public function handleLogin() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha_input = $_POST['captcha'] ?? '';

        // 1. Kiểm tra CAPTCHA
        if (strtoupper($captcha_input) !== ($_SESSION['captcha_code'] ?? '')) {
            $error = "Mã xác nhận (CAPTCHA) không chính xác!";
            require_once 'app/Views/auth/login.php';
            return;
        }

        try {
            // 2. TRUY VẤN DATABASE CHUẨN XÁC THEO BẢNG CỦA BẠN
            // Lấy u.* từ bảng users, lấy role_name và level từ bảng roles
            $stmt = $this->pdo->prepare("SELECT u.*, r.role_name, r.level 
                                         FROM users u 
                                         LEFT JOIN roles r ON u.role_id = r.id 
                                         WHERE u.username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. KIỂM TRA MẬT KHẨU MÃ HÓA VÀ TRẠNG THÁI
            if ($user && password_verify($password, $user['password'])) {
                
                // Chặn nếu tài khoản bị khóa
                if (isset($user['is_active']) && $user['is_active'] == 0) {
                    $error = "Tài khoản của bạn đang bị khóa! Vui lòng liên hệ Admin.";
                    require_once 'app/Views/auth/login.php';
                    return;
                }

                // Lưu Session (Sử dụng đúng role_name và level)
                $_SESSION['user_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role_name'] ?? 'Không có quyền';
                $_SESSION['role_level'] = (int)($user['level'] ?? 4);

                // Cập nhật mốc thời gian vào last_login
                $updateStmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$user['id']]);

                // Đăng nhập thành công, bay thẳng vào trong
                header("Location: index.php?page=home");
                exit();
                
            } else {
                $error = "Tài khoản hoặc mật khẩu không chính xác!";
                require_once 'app/Views/auth/login.php';
            }
            
        } catch (PDOException $e) {
            // Hiển thị lỗi gốc nếu SQL có vấn đề (hữu ích cho việc debug)
            $error = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
            require_once 'app/Views/auth/login.php';
        }
    }

    // Xử lý Đăng xuất
    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}