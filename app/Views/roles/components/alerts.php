<?php if(isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success py-2 small fw-bold"><i class="fas fa-check-circle me-1"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger py-2 small fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
<?php endif; ?>
