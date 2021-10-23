<main class="flex min-h-screen items-center justify-center px-6">
    <div class="w-full max-w-lg rounded-[2rem] border border-white/10 bg-white/5 p-8 text-center shadow-2xl backdrop-blur">
        <div class="mx-auto flex h-16 w-16 animate-spin items-center justify-center rounded-full border-4 border-cyan-400/20 border-t-cyan-300"></div>
        <p class="mt-6 text-xs font-semibold text-cyan-200">Redirect in progress</p>
        <h1 class="mt-3 text-3xl font-extrabold">Taking you there now</h1>
        <p class="mt-4 break-all rounded-2xl bg-white/5 px-4 py-3 text-sm text-slate-200"><?= e($targetUrl)?></p>
        <a href="<?= e($targetUrl)?>" class="mt-6 inline-flex rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950">Continue manually</a>
    </div>
</main>

<script>
    const redirectConfig = {
        trackUrl: <?= json_encode(app_url('track/' . $trackingKey), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)?>,
        targetUrl: <?= json_encode($targetUrl, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)?>,
        redirectType: <?=(int)$redirectType?>
    };

    const payload = {
        screen_resolution: `${window.screen.width}x${window.screen.height}`,
        language: navigator.language || '',
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || '',
        fingerprint: [
            navigator.userAgent || '',
            navigator.platform || '',
            screen.width,
            screen.height,
            navigator.language || '',
            Intl.DateTimeFormat().resolvedOptions().timeZone || ''
        ].join('|')
    };

    const redirectNow = () => {
        window.location.replace(redirectConfig.targetUrl);
    };

    const body = JSON.stringify(payload);
    if (navigator.sendBeacon) {
        navigator.sendBeacon(redirectConfig.trackUrl, new Blob([body], { type: 'application/json' }));
        setTimeout(redirectNow, 120);
    } else {
        fetch(redirectConfig.trackUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body
        }).finally(() => setTimeout(redirectNow, 120));
    }

    setTimeout(redirectNow, 800);
</script>
<noscript>
    <meta http-equiv="refresh" content="0;url=<?= e($targetUrl)?>">
</noscript>
