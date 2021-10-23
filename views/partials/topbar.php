<?php
$activePage = $activePage ?? '';
$currentUser = current_user();
?>
<header class="border-b border-white/60 bg-white/70 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="<?= e(app_url())?>" class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-sky-500 text-lg font-extrabold text-white shadow-glow">
                <?= icon('link', 'h-5 w-5')?>
            </div>
            <div>
                <p class="text-sm font-semibold tracking-[0.1em] text-blue-600 dark:text-blue-300">
                    Linkshortner</p>
            </div>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <?php if ($currentUser): ?>
            <a href="<?= e(app_url('dashboard'))?>"
                class="inline-flex rounded-full px-4 py-2 text-sm font-semibold transition <?= $activePage === 'dashboard' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'border border-slate-200 bg-white text-slate-700 hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100'?>">
                <?= icon('chart-bar', 'mr-2 h-4 w-4')?>
                My Links
            </a>
            <form action="<?= e(app_url('logout'))?>" method="POST" class="inline-flex">
                <?= CsrfTokenManager::field()?>
                <button type="submit"
                    class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <?= icon('arrow-right-on-rectangle', 'mr-2 h-4 w-4')?>
                    Logout
                </button>
            </form>
            <?php
else: ?>
            <a href="<?= e(app_url('login'))?>"
                class="hidden rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 sm:inline-flex">
                <?= icon('arrow-right-on-rectangle', 'mr-2 h-4 w-4')?>
                Log in
            </a>
            <a href="<?= e(app_url('signup'))?>"
                class="hidden rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 sm:inline-flex">
                <?= icon('user-circle', 'mr-2 h-4 w-4')?>
                Sign up
            </a>
            <?php
endif; ?>

            <button type="button" data-theme-toggle
                class="inline-flex items-center rounded-full border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                <?= icon('moon', 'h-5 w-5')?>
            </button>
        </div>
    </div>
</header>