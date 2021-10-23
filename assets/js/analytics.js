document.addEventListener('DOMContentLoaded', () => {
    const dataNode = document.getElementById('analyticsData');
    if (!dataNode) return;

    const data = JSON.parse(dataNode.textContent);
    if (!data.hasData) return;

    // Chart.js Default Styles
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b';

    // Timeline Chart
    const ctxTimeline = document.getElementById('timelineChart');
    if (ctxTimeline) {
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: data.timeline.map(item => item.label),
                datasets: [{
                    label: 'Clicks',
                    data: data.timeline.map(item => item.clicks),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Device Chart
    const ctxDevice = document.getElementById('deviceChart');
    if (ctxDevice) {
        new Chart(ctxDevice, {
            type: 'doughnut',
            data: {
                labels: data.deviceBreakdown.map(item => item.label || 'Unknown'),
                datasets: [{
                    data: data.deviceBreakdown.map(item => item.clicks),
                    backgroundColor: ['#3b82f6', '#0ea5e9', '#6366f1', '#8b5cf6', '#ec4899'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '70%'
            }
        });
    }

    // Browser Chart
    const ctxBrowser = document.getElementById('browserChart');
    if (ctxBrowser) {
        new Chart(ctxBrowser, {
            type: 'doughnut',
            data: {
                labels: data.browserBreakdown.map(item => item.label || 'Unknown'),
                datasets: [{
                    data: data.browserBreakdown.map(item => item.clicks),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '70%'
            }
        });
    }

    // Heatmap (Basic CSS-based grid)
    const heatmapGrid = document.getElementById('heatmapGrid');
    if (heatmapGrid && data.hourlyHeatmap) {
        const hours = Array.from({ length: 24 }, (_, i) => i);
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        // Simple visualization for now as grid was already there
        // But we can improve it if needed.
    }
});
