</main> <!-- Đóng thẻ main.content-wrapper -->
    </div> <!-- Đóng thẻ div.main-content -->
</div> <!-- Đóng thẻ div.wrapper -->

<!-- Bootstrap 5 JS Bundle (Kèm Popper để chạy Dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script tương tác UI chung -->
<script>
    // Xử lý nút bấm ẩn/hiện Sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        if (sidebar.style.marginLeft === '-250px') {
            sidebar.style.marginLeft = '0';
        } else {
            sidebar.style.marginLeft = '-250px';
        }
    });
</script>

<!-- Chỗ này để các thành viên viết thêm JS riêng của từng trang -->
</body>
</html>