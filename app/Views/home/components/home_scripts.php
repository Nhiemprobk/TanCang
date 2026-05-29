<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const realRevenueInMillions = <?= round($totalRevenue / 1000000, 1) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Hôm nay'],
            datasets: [{
                label: 'Doanh thu (Triệu VNĐ)',
                data: [15.2, 22.5, 18.0, 30.1, 25.8, 40.0, realRevenueInMillions],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(25, 135, 84, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(25, 135, 84, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
