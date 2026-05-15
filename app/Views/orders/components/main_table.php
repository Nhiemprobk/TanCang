<div class="table-responsive table-scrollable border rounded">
    <table class="table table-sm table-compact table-hover table-striped text-center align-middle text-nowrap mb-0" style="font-size: 13px;">
        <thead class="table-primary text-white" style="background-color: #0284c7;">
            <tr>
                <th class="align-middle border-end">STT</th>
                <th class="align-middle border-end">Tác vụ</th>
                <th class="align-middle border-end">Mã đơn</th>
                <th class="align-middle border-end">Ngày gửi đơn <i class="fas fa-sort text-white-50 ms-1"></i></th>
                <th class="align-middle border-end">Người tạo</th>
                
                <th class="align-middle border-end">Ngày duyệt/từ chối <i class="fas fa-sort text-white-50 ms-1"></i></th>
                <th class="align-middle border-end">Người duyệt</th>
                <th class="align-middle border-end">Ngày hoàn thành <i class="fas fa-sort text-white-50 ms-1"></i></th>
                
                <th class="align-middle border-end">Phương án</th>
                <th class="align-middle border-end">Depot</th>
                <th class="align-middle border-end">Hãng tàu</th>
                <th class="align-middle border-end">BL/DO/BKG</th>
                
                <th class="align-middle border-end">Chi tiết Cont</th>
                <th class="align-middle border-end">Thành tiền</th>
                
                <th class="align-middle">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $index => $row): ?>
                <tr>
                    <td class="border-end"><?= $index + 1 ?></td>
                    <td class="border-end text-nowrap">
                        <a href="<?= $baseUrl ?>/index.php?page=edit_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex justify-content-center align-items-center" title="Sửa" style="width:28px; height:28px;">
                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                        </a>

                        <a href="<?= $baseUrl ?>/index.php?page=delete_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle mx-1 d-inline-flex justify-content-center align-items-center" title="Xóa" style="width:28px; height:28px;" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                        </a>

                        <a href="<?= $baseUrl ?>/index.php?page=download_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success rounded-circle d-inline-flex justify-content-center align-items-center" title="Tải xuống" style="width:28px; height:28px;">
                            <i class="fas fa-download" style="font-size: 12px;"></i>
                        </a>
                    </td>
                    <td class="fw-bold text-primary border-end"><?= htmlspecialchars($row['order_code']) ?></td>
                    <td class="border-end"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td class="border-end"><?= htmlspecialchars($row['creator_name']) ?></td>
                    
                    <td class="border-end"><?= !empty($row['approval_date']) ? date('d/m/Y H:i', strtotime($row['approval_date'])) : '' ?></td>
                    <td class="border-end"><?= htmlspecialchars($row['approver_name'] ?? '') ?></td>
                    <td class="border-end"><?= !empty($row['completion_date']) ? date('d/m/Y H:i', strtotime($row['completion_date'])) : '' ?></td>
                    
                    <td class="border-end">
                        <?php if($row['action_type'] == 'Hạ rỗng'): ?>
                            <span class="badge bg-info text-dark">Hạ rỗng</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Cấp rỗng</span>
                        <?php endif; ?>
                    </td>
                    <td class="border-end"><?= htmlspecialchars($row['depot_name']) ?></td>
                    <td class="border-end fw-semibold"><?= htmlspecialchars($row['shipping_line']) ?></td>
                    <td class="border-end"><?= htmlspecialchars($row['bl_do_bkg']??'') ?></td>
                    
                    <td class="border-end">
                        <span class="badge bg-light text-dark border"><?= $row['container_details'] ?: 'N/A' ?></span>
                    </td>
                    <td class="text-end fw-bold text-primary border-end">
                        <?= number_format($row['total_order_price'] ?: 0, 0, ',', '.') ?>đ
                    </td>
                    
                    <td><?= htmlspecialchars($row['note']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="15" class="text-center py-3 text-muted">Chưa có dữ liệu</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>