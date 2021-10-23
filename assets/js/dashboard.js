document.addEventListener('DOMContentLoaded', () => {
    const loadingNotice = document.getElementById('analyticsLoading');
    if (loadingNotice) {
        window.setTimeout(() => {
            loadingNotice.classList.add('page-loader-hidden');
            window.setTimeout(() => loadingNotice.remove(), 220);
        }, 180);
    }
});
