<?php

class AuthController {
    
    // Hiển thị form đăng nhập
    public function showLogin() {
        require_once 'app/Views/auth/login.php';
    }

    // Xử lý khi user bấm nút Submit
    public function handleLogin() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $captcha_input = $_POST['captcha'] ?? '';

        // 1. Kiểm tra CAPTCHA đầu tiên
        if (strtoupper($captcha_input) !== $_SESSION['captcha_code']) {
            $error = "Mã xác nhận (CAPTCHA) không chính xác!";
            require_once 'app/Views/auth/login.php';
            return; // Dừng lại không xử lý tiếp
        }

        // 2. MÔ PHỎNG KIỂM TRA MẬT KHẨU (Sau này bạn sẽ nối với DB ở đây)
        // Hiện tại gán cứng: tk: admin, mk: 123456
        if ($username === 'admin' && $password === '123456') {
            // Đăng nhập thành công, lưu thông tin vào Session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'Admin';
            
            //  Lấy level từ bảng Roles
            $_SESSION['role_level'] = (int)$user['level'];

            // Chuyển hướng vào trang chủ hệ thống
            header("Location: index.php?page=home");
            exit();
        } else {
            $error = "Tài khoản hoặc mật khẩu không đúng!";
            require_once 'app/Views/auth/login.php';
        }
    }
}
?>