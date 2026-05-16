<?php

class UserController {
    private $pdo;

    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 2) {
            header("Location: index.php?page=orders");
            exit();
        }
        // ==========================================================
        // 1. XỬ LÝ CÁC HÀNH ĐỘNG GET (KHÓA/MỞ KHÓA, XÓA)
        // ==========================================================
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $action = $_GET['action'];
            $id = (int)$_GET['id'];

            if ($action == 'toggle_status') {
                // Đảo ngược trạng thái is_active (1 thành 0, 0 thành 1)
                $stmt = $this->pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$id]);
                header("Location: index.php?page=users&msg=status_changed");
                exit;
            }

            if ($action == 'delete') {
                // Xóa vĩnh viễn
                $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                header("Location: index.php?page=users&msg=deleted");
                exit;
            }
        }

        // ==========================================================
        // 2. XỬ LÝ CÁC HÀNH ĐỘNG POST (THÊM, SỬA, ĐỔI MẬT KHẨU)
        // ==========================================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'add') {
                $username = trim($_POST['username']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $full_name = trim($_POST['full_name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $role_id = $_POST['role_id'];

                try {
                    $sql = "INSERT INTO users (username, password, full_name, email, phone, role_id, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$username, $password, $full_name, $email, $phone, $role_id]);
                    header("Location: index.php?page=users&msg=added");
                    exit;
                } catch(PDOException $e) {
                    $error_msg = "Tên đăng nhập đã tồn tại!";
                }
            }

            if ($action == 'edit') {
                $id = $_POST['id'];
                $full_name = trim($_POST['full_name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $role_id = $_POST['role_id'];

                $sql = "UPDATE users SET full_name=?, email=?, phone=?, role_id=? WHERE id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$full_name, $email, $phone, $role_id, $id]);
                header("Location: index.php?page=users&msg=edited");
                exit;
            }

            if ($action == 'reset_password') {
                $id = $_POST['id'];
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                $sql = "UPDATE users SET password=? WHERE id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$new_password, $id]);
                header("Location: index.php?page=users&msg=password_reset");
                exit;
            }
        }

        // ==========================================================
        // 3. LẤY DỮ LIỆU VÀ THỐNG KÊ (GIỮ NGUYÊN)
        // ==========================================================
        $stmt = $this->pdo->query("SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 1; })),
            'locked' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 0; }))
        ];

        require 'app/Views/users/index.php';
    }
}
?>