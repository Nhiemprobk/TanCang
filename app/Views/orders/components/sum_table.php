<div class="row justify-content-center mb-4">
    <div class="col-md-9">
        <div class="dash-card p-3">
            <div class="table-responsive border rounded">
                <table class="table table-bordered table-sm text-center align-middle mb-0" style="font-size: 13px;">
                    <thead style="background-color: #0284c7; color: white;">
                        <tr>
                            <th class="py-2">SUM</th>
                            <th class="py-2">20'GP</th><th class="py-2">40'GP</th><th class="py-2">40'HC</th>
                            <th class="py-2">20'OT</th><th class="py-2">20'FL</th><th class="py-2">40'FL</th>
                            <th class="py-2">40'OT</th><th class="py-2">45'</th>
                            <th class="py-2 bg-primary text-white">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold bg-light">Hạ rỗng</td>
                            <td><?= $summary['ha_rong']['20'] ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="<?= $summary['ha_rong']['40'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['ha_rong']['40'] ?></td>
                            <td class="<?= $summary['ha_rong']['45'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['ha_rong']['45'] ?></td>
                            <td class="fw-bold text-primary"><?= $summary['ha_rong']['tong'] ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Cấp rỗng</td>
                            <td class="<?= $summary['cap_rong']['20'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['20'] ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="<?= $summary['cap_rong']['40'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['40'] ?></td>
                            <td class="<?= $summary['cap_rong']['45'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['45'] ?></td>
                            <td class="fw-bold text-primary"><?= $summary['cap_rong']['tong'] ?></td>
                        </tr>
                        <tr class="table-secondary">
                            <td class="fw-bold">Tổng (CONT)</td>
                            <?php 
                                $tong20 = $summary['ha_rong']['20'] + $summary['cap_rong']['20'];
                                $tong40 = $summary['ha_rong']['40'] + $summary['cap_rong']['40'];
                                $tong45 = $summary['ha_rong']['45'] + $summary['cap_rong']['45'];
                                $tongAll = $summary['ha_rong']['tong'] + $summary['cap_rong']['tong'];
                            ?>
                            <td class="fw-bold <?= $tong20 > 0 ? 'text-danger' : '' ?>"><?= $tong20 ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="fw-bold <?= $tong40 > 0 ? 'text-danger' : '' ?>"><?= $tong40 ?></td>
                            <td class="fw-bold <?= $tong45 > 0 ? 'text-danger' : '' ?>"><?= $tong45 ?></td>
                            <td class="fw-bold text-danger fs-6"><?= $tongAll ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>