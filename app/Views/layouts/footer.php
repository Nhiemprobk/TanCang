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

<script>
    // Khai báo đường dẫn API sử dụng biến $baseUrl để tránh lỗi 404
    const apiNotification = '<?= $baseUrl ?>/api.php';

    async function pollNotifications() {
        try {
            const res = await fetch(apiNotification + '?action=fetch');
            const data = await res.json();
            
            // Cập nhật số lượng huy hiệu (badge)
            const countEl = document.getElementById('notiCount');
            countEl.innerText = data.unread_count;
            countEl.style.display = data.unread_count > 0 ? 'block' : 'none';
            
            // Xử lý khung hiển thị thông báo
            const notiList = document.getElementById('notiList');
            if(data.notifications.length === 0) {
                notiList.innerHTML = '<div class="text-center p-4 text-muted small"><i class="fas fa-box-open fs-3 mb-2 opacity-50"></i><br>Bạn đã đọc hết thông báo</div>';
                return;
            }
            
            // Đảo ngược mảng để thông báo mới lên đầu và Render HTML
            notiList.innerHTML = data.notifications.reverse().map(n => `
                <div class="noti-item ${n.is_read ? '' : 'unread'} level-${n.level}" onclick="readNotification(${n.id}, event)">
                    <h6><i class="fas fa-circle ms-1 fs-6 float-end ${n.is_read ? 'text-transparent' : 'text-primary'}"></i> ${n.title}</h6>
                    <p>${n.body}</p>
                    <span class="time"><i class="far fa-clock me-1"></i> ${n.created_at}</span>
                </div>
            `).join('');
        } catch (error) {
            console.error("Lỗi khi tải thông báo:", error);
        }
    }

    async function readNotification(id, e) {
        // Ngăn chặn việc click làm Dropdown của Bootstrap tự đóng lại
        e.stopPropagation(); 
        await fetch(apiNotification + '?action=mark_read', {
            method: 'POST',
            body: JSON.stringify({id})
        });
        // Tải lại danh sách ngay lập tức để xóa viền xanh "chưa đọc"
        pollNotifications(); 
    }

    async function markAllRead(e) {
        e.stopPropagation(); // Giữ cho bảng thông báo không bị đóng khi click
        await fetch(apiNotification + '?action=mark_all_read', { method: 'POST' });
        pollNotifications(); // Tải lại giao diện
    }

    async function deleteAllNoti(e) {
        e.stopPropagation();
        await fetch(apiNotification + '?action=delete_all', { method: 'POST' });
        pollNotifications();
    }

    // Gọi lần đầu và thiết lập lặp lại mỗi 3 giây (Polling)
    pollNotifications();
    setInterval(pollNotifications, 3000);
</script>

</body>
</html>