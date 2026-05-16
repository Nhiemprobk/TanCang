<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Quản trị hệ thống tài khoản</h4>
        <p class="text-muted small mb-0 mt-1">Cấp quyền và giám sát hoạt động của nhân viên & khách hàng</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus-circle me-1"></i> Tạo tài khoản mới
    </button>
</div>

<?php include 'components/stats_cards.php'; ?>

<?php include 'components/user_table.php'; ?>

<?php include 'components/add_modal.php'; ?>
<?php include 'components/edit_modal.php'; ?>
<?php include 'components/reset_pass_modal.php'; ?>

<script>
    function fillEditModal(el) {
        document.getElementById('edit_id').value = el.getAttribute('data-id');
        document.getElementById('edit_username').value = el.getAttribute('data-username');
        document.getElementById('edit_full_name').value = el.getAttribute('data-fullname');
        document.getElementById('edit_email').value = el.getAttribute('data-email');
        document.getElementById('edit_phone').value = el.getAttribute('data-phone');
        document.getElementById('edit_role_id').value = el.getAttribute('data-role');
    }

    function fillResetModal(el) {
        document.getElementById('reset_id').value = el.getAttribute('data-id');
        document.getElementById('reset_username').innerText = '@' + el.getAttribute('data-username');
    }

    function validateAddUserForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
            alert('Mật khẩu đã nhập không khớp. Thử lại.');
            return false;
        }
        return true;
    }
</script>

<?php if(isset($_GET['msg'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const msgs = {
            'added': '🎉 Tạo tài khoản thành công!',
            'edited': '✅ Cập nhật thông tin thành công!',
            'password_reset': '🔑 Đã cấp lại mật khẩu mới thành công!',
            'status_changed': '🔄 Đã thay đổi trạng thái tài khoản!',
            'deleted': '🗑️ Đã xóa tài khoản vĩnh viễn!',
            'password_mismatch': '⚠️ Mật khẩu đã nhập không khớp. Thử lại.',
            'user_exists': '⚠️ Tên đăng nhập hoặc email đã tồn tại. Vui lòng thử lại.'
        };
        const msgType = '<?= $_GET['msg'] ?>';
        if(msgs[msgType]) alert(msgs[msgType]);
        window.history.pushState({}, document.title, window.location.pathname + "?page=users");
    });
</script>
<?php endif; ?>

<style>
    .ripple-success { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); animation: ripple 1.5s infinite; }
    @keyframes ripple { 0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(25, 135, 84, 0); } 100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); } }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; transition: all 0.2s ease; }
</style>

<?php require_once 'app/Views/layouts/footer.php'; ?>