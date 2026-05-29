<div class="dash-card p-4">
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center mb-0" style="font-size: 13px;">
            <thead class="table-light">
                <tr>
                    <th width="60">ID</th>
                    <th width="120">Cấp độ Zone</th>
                    <th width="200">Tên Nhóm Quyền</th>
                    <th class="text-start">Mô tả giới hạn chức năng</th>
                    <th width="100">Tác vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($roles)): ?>
                    <?php foreach($roles as $role): ?>
                    <tr>
                        <td class="fw-bold text-muted"><?= $role['id'] ?></td>
                        
                        <td>
                            <?php if($role['level'] == 1): ?>
                                <span class="badge bg-danger px-2 py-1">Cấp 1 (Admin)</span>
                            <?php elseif($role['level'] == 2): ?>
                                <span class="badge bg-warning text-dark px-2 py-1">Cấp 2 (Quản lý)</span>
                            <?php elseif($role['level'] == 3): ?>
                                <span class="badge bg-primary px-2 py-1">Cấp 3 (Nhân viên)</span>
                            <?php elseif($role['level'] == 4): ?>
                                <span class="badge bg-info text-dark px-2 py-1">Cấp 4 (Kế toán)</span>
                            <?php endif; ?>
                        </td>
                        
                        <td><span class="fw-bold text-dark"><?= htmlspecialchars($role['role_name']) ?></span></td>
                        <td class="text-start text-muted"><?= htmlspecialchars($role['description'] ?? 'Chưa có mô tả') ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa quyền" style="width:28px; height:28px;" href="<?= $baseUrl ?>/index.php?page=delete_role&id=<?= $role['id'] ?>" onclick="return confirm('Xác nhận xóa nhóm quyền này?')">
                                <i class="fas fa-trash" style="font-size:11px;"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-3 text-muted">Chưa có nhóm quyền nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
