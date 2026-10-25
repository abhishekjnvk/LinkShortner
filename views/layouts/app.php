<?php
$pageTitle = $pageTitle ?? config('app.name');
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Production-ready URL shortener with secure analytics, QR codes, charts, and mobile-first dashboards.">
    <script>
        const preferredTheme = localStorage.getItem('theme');
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (preferredTheme === 'dark' || (!preferredTheme && systemDark)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        },
                        ink: {
                            950: '#050816'
                        }
                    },
                    boxShadow: {
                        soft: '0 18px 50px rgba(15, 23, 42, 0.08)',
                        glow: '0 10px 35px rgba(37, 99, 235, 0.22)'
                    },
                    animation: {
                        float: 'float 8s ease-in-out infinite',
                        pulseSoft: 'pulseSoft 1.8s ease-in-out infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        pulseSoft: {
                            '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                            '50%': { transform: 'scale(1.03)', opacity: '0.86' }
                        }
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
    <?php if (!empty($extraHeadScripts ?? null)): ?>
        <?php foreach ($extraHeadScripts as $scriptUrl): ?>
            <script src="<?= e($scriptUrl) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="min-h-full bg-stone-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-ink-950 dark:text-slate-100">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-blue-100/70 blur-3xl dark:bg-blue-500/18"></div>
        <div class="absolute right-0 top-28 h-80 w-80 rounded-full bg-sky-100/80 blur-3xl dark:bg-sky-500/12"></div>
        <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-indigo-100/60 blur-3xl dark:bg-indigo-500/10"></div>
    </div>
    <div class="relative flex min-h-screen flex-col">
        <?= $content ?>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </div>
    <script src="<?= e(asset_url('js/app.js')) ?>"></script>
    <?php if (!empty($includeChartJs ?? null)): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <?php endif; ?>
    <?php if (!empty($includeQrJs ?? null)): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <?php endif; ?>
    <?php if (!empty($pageScript ?? null)): ?>
        <script src="<?= e(asset_url('js/' . $pageScript)) ?>"></script>
    <?php endif; ?>
</body>
</html>
