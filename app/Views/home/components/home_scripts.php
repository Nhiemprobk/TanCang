<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const revenueLabels = <?= json_encode($revenueLabels ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const revenueData = <?= json_encode($revenueData ?? [], JSON_UNESCAPED_UNICODE) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu (Triệu VNĐ)',
                data: revenueData,
                backgroundColor: revenueLabels.map((label, index) => {
                    return label === 'Hôm nay'
                        ? 'rgba(25, 135, 84, 0.8)'
                        : 'rgba(54, 162, 235, 0.4)';
                }),
                borderColor: revenueLabels.map((label, index) => {
                    return label === 'Hôm nay'
                        ? 'rgba(25, 135, 84, 1)'
                        : 'rgba(54, 162, 235, 1)';
                }),
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' triệu VNĐ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        callback: function(value) {
                            return value + 'tr';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>