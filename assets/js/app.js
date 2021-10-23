document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.querySelector('[data-theme-toggle]');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const root = document.documentElement;
            root.classList.toggle('dark');
            localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        });
    }

    document.querySelectorAll('[data-copy-text]').forEach((button) => {
        button.addEventListener('click', async () => {
            const text = button.getAttribute('data-copy-text') || '';
            const label = button.innerHTML;

            try {
                await navigator.clipboard.writeText(text);
                button.innerHTML = `${iconMarkup('check-circle')}<span>Copied</span>`;
                button.classList.add('copy-success');

                window.setTimeout(() => {
                    button.innerHTML = label;
                    button.classList.remove('copy-success');
                }, 1600);
            } catch (error) {
                button.innerHTML = `${iconMarkup('exclamation-triangle')}<span>Copy failed</span>`;
            }
        });
    });

    const qrNode = document.getElementById('qr-code');
    if (qrNode && window.QRCode) {
        new QRCode(qrNode, {
            text: qrNode.getAttribute('data-qr-text') || '',
            width: 148,
            height: 148,
            colorDark: '#0f172a',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }

    const expirationPreset = document.querySelector('[data-expiration-preset]');
    const customExpirationWrapper = document.querySelector('[data-expiration-custom-wrapper]');
    const customExpirationInput = document.getElementById('expires_at_custom');

    if (expirationPreset && customExpirationWrapper && customExpirationInput) {
        const syncExpirationField = () => {
            const isCustom = expirationPreset.value === 'custom';
            customExpirationWrapper.classList.toggle('hidden', !isCustom);
            customExpirationInput.disabled = !isCustom;
        };

        syncExpirationField();
        expirationPreset.addEventListener('change', syncExpirationField);
    }

    document.querySelectorAll('[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button) {
                return;
            }

            form.classList.add('is-loading');
            button.disabled = true;
            button.classList.add('opacity-80');
            const label = button.querySelector('[data-button-label]');
            const loading = button.querySelector('[data-button-loading]');
            if (label && loading) {
                label.classList.add('hidden');
                loading.classList.remove('hidden');
                loading.classList.add('inline-flex');
            }
        });
    });
});

function iconMarkup(name) {
    const map = {
        'check-circle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
        'exclamation-triangle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm8.257 3.099c.765 1.36-.213 3.026-1.746 3.026H5.49c-1.533 0-2.51-1.666-1.746-3.026L10.256 3.62c.766-1.36 2.72-1.36 3.486 0l6.515 11.979Z"/></svg>',
    };

    return map[name] || '';
}
