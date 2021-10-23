<?php $activePage = ''; ?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main class="mx-auto flex min-h-[calc(100vh-88px)] max-w-4xl items-center px-4 py-12 sm:px-6 lg:px-8">
    <section
        class="w-full rounded-[2rem] border border-amber-200/70 bg-white/90 p-8 shadow-soft dark:border-amber-900/50 dark:bg-slate-900/90">
        <div class="mx-auto max-w-2xl text-center">
            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-200">
                <?= icon('shield-exclamation', 'h-8 w-8')?>
            </div>
            <p class="mt-5 text-sm font-semibold text-amber-600 dark:text-amber-300">429 Too Many
                Requests</p>
            <h1 class="mt-3 text-4xl font-extrabold text-slate-950 dark:text-white">You are moving a little too fast.
            </h1>
            <p class="mt-4 text-base text-slate-600 dark:text-slate-300">
                This short link is temporarily rate limited to keep traffic fair and reduce abuse.
                Please wait about <span class="font-semibold text-slate-950 dark:text-white">
                    <?= e((string)$retryAfter)?> seconds
                </span> before trying again.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div
                    class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm font-semibold tracking-[0.2em] text-slate-400">Current limit</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">
                        <?= e((string)$limit)?>
                    </p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">requests per minute</p>
                </div>
                <div
                    class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm font-semibold tracking-[0.2em] text-slate-400">Access type</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">
                        <?= $isAuthenticated ? 'Signed-in session' : 'Guest visitor'?>
                    </p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        <?= $isAuthenticated ? 'Authenticated sessions may use a higher limit.' : 'Signing in can use a separate limit.'?>
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="<?= e(app_url())?>"
                    class="inline-flex items-center gap-2 rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                    <?= icon('home', 'h-4 w-4')?>
                    Back home
                </a>
                <?php if (!$isAuthenticated): ?>
                <a href="<?= e(app_url('login'))?>"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-slate-700 dark:text-slate-100">
                    <?= icon('arrow-right-on-rectangle', 'h-4 w-4')?>
                    Log in
                </a>
                <?php
endif; ?>
            </div>
        </div>
    </section>
</main>