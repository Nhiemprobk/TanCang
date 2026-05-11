</main> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Xử lý nút bấm ẩn/hiện Sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        // Phải đồng bộ với độ rộng 260px trong CSS
        if (sidebar.style.marginLeft === '-260px') {
            sidebar.style.marginLeft = '0';
        } else {
            sidebar.style.marginLeft = '-260px';
        }
    });
</script>

</body>
</html>