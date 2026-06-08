<?php 
require_once 'app/Views/layouts/header.php';
?>

<?php include 'components/page_header.php'; ?>
<?php include 'components/metrics_cards.php'; ?>
<div class="row g-4">
    <?php include 'components/chart_panel.php'; ?>
    <?php include 'components/recent_activity.php'; ?>
</div>

<?php include 'components/report_section.php'; ?>

<?php include 'components/home_scripts.php'; ?>

<?php require_once 'app/Views/layouts/footer.php'; ?>