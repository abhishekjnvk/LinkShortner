<?php
$includeQrJs = !empty($result);
$activePage = 'home';
?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main>
    <section class="mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 lg:px-8 lg:pb-24 lg:pt-14">
        <div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <div class="space-y-6">
                <span
                    class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700 dark:border-blue-900/70 dark:bg-blue-950/40 dark:text-blue-200">
                    <?= icon('sparkles', 'mr-2 h-4 w-4')?> URL shortener
                </span>
                <div class="space-y-4">
                    <h1
                        class="max-w-3xl text-4xl font-extrabold leading-tight text-slate-950 sm:text-5xl lg:text-6xl dark:text-white">
                        Short links made simple.
                    </h1>
                    <p class="max-w-2xl text-lg text-slate-600 dark:text-slate-300">
                        Paste a long URL and get a clean, short link with analytics instantly.
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -right-6 -top-6 hidden h-24 w-24 rounded-full bg-blue-400/30 blur-2xl lg:block">
                </div>
                <div class="absolute -bottom-6 -left-6 hidden h-24 w-24 rounded-full bg-sky-400/30 blur-2xl lg:block">
                </div>
                <?php
$returnTo = '/';
$formHeading = 'Shorten any link';
$submitLabel = 'Generate short URL';
$showHeaderBadge = false;
include __DIR__ . '/../partials/link-create-form.php';
?>
            </div>
        </div>
    </section>

    <section
        class="border-t max-w-6xl my-8 mb-36 mx-auto border-slate-100 bg-slate-50/20 py-16 dark:border-slate-800 dark:bg-slate-900/20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-3">
                <div class="flex flex-col items-center text-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                        <?= icon('bolt', 'h-6 w-6')?>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">Lightning Fast</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Shorten URLs in seconds
                        and
                        share them anywhere instantly without any friction.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-300">
                        <?= icon('chart-pie', 'h-6 w-6')?>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">Detailed Insights</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Track clicks, geographic
                        data, and device types with our beautiful analytics suite.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                        <?= icon('shield-check', 'h-6 w-6')?>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">Secure & Private</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        Get private analytics for your links and keep your data safe for loggedin user.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>