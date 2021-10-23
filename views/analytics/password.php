<?php $activePage = ''; ?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main class="mx-auto flex min-h-[calc(100vh-88px)] max-w-7xl items-center px-4 py-12 sm:px-6 lg:px-8">
    <div
        class="mx-auto w-full max-w-md rounded-[2rem] border border-white/80 bg-white/90 p-8 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
        <div class="flex items-center gap-3">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-950/50 dark:text-cyan-200">
                <?= icon('shield-check', 'h-6 w-6')?>
            </div>
            <div>
                <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Protected analytics
                </p>
                <h1 class="mt-1 text-2xl font-extrabold text-slate-950 dark:text-white">Unlock /
                    <?= e($urlRecord['alias'])?>
                </h1>
            </div>
        </div>
        <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-slate-400">This link uses an optional analytics
            password. Enter it below to open the dashboard.</p>

        <?php if ($status): ?>
        <div
            class="mt-5 inline-flex w-full items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
            <?= icon('exclamation-triangle', 'mt-0.5 h-5 w-5 shrink-0')?>
            <span>
                <?= e($status['message'])?>
            </span>
        </div>
        <?php
endif; ?>

        <form action="<?= e(app_url('analytics/' . $urlRecord['alias'] . '/unlock'))?>" method="POST"
            class="mt-6 space-y-4" data-loading-form>
            <?= CsrfTokenManager::field()?>
            <div>
                <label for="password"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Dashboard
                    password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-cyan-400 focus:ring-cyan-300 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-glow transition hover:-translate-y-0.5">
                <span data-button-label class="inline-flex items-center gap-2">
                    <?= icon('shield-check', 'h-4 w-4')?>
                    Unlock analytics
                </span>
                <span data-button-loading class="hidden items-center gap-2">
                    <?= icon('arrow-path', 'h-4 w-4 animate-spin')?>
                    Checking...
                </span>
            </button>
        </form>
    </div>
</main>