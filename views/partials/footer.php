<footer class="border-t border-white/60 bg-white/70 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70">
    <div
        class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 text-sm text-slate-500 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 dark:text-slate-400">
        <div class="flex items-center gap-2">
            <span
                class="inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-slate-950 text-white dark:bg-white dark:text-slate-950">
                <?= icon('link', 'h-4 w-4')?>
            </span>
            <p>Short links, simple analytics, optional accounts.</p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <a href="<?= e(app_url())?>" class="transition hover:text-slate-900 dark:hover:text-white">Home</a>
            <?php if (is_authenticated()): ?>
            <a href="<?= e(app_url('dashboard'))?>" class="transition hover:text-slate-900 dark:hover:text-white">My
                Links</a>
            <?php
else: ?>
            <a href="<?= e(app_url('login'))?>" class="transition hover:text-slate-900 dark:hover:text-white">Log
                in</a>
            <a href="<?= e(app_url('signup'))?>" class="transition hover:text-slate-900 dark:hover:text-white">Sign
                up</a>
            <?php
endif; ?>
        </div>
    </div>
</footer>