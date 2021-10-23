<?php $activePage = 'login'; ?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main class="mx-auto flex min-h-[calc(100vh-88px)] max-w-7xl items-center px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid w-full gap-8 lg:grid-cols-[1fr_480px] lg:items-center">
        <section class="space-y-6">
            <h1 class="max-w-2xl text-4xl font-extrabold leading-tight text-slate-950 sm:text-5xl lg:text-6xl dark:text-white">
                Manage your links from any device.
            </h1>
            <p class="max-w-2xl text-lg text-slate-600 dark:text-slate-300">
                Sign in to access your personal dashboard, track detailed analytics, and manage your shortened URLs with ease.
            </p>
        </section>

        <section
            class="rounded-[2rem] border border-white/80 bg-white/90 p-8 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
            <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Log in</p>
            <h2 class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">Welcome back</h2>

            <?php if ($status): ?>
            <div
                class="mt-5 rounded-2xl border px-4 py-3 text-sm font-medium <?= $status['type'] === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200'?>">
                <span class="inline-flex items-center gap-2">
                    <?= $status['type'] === 'success' ? icon('check-circle', 'h-5 w-5') : icon('exclamation-triangle', 'h-5 w-5')?>
                    <?= e($status['message'])?>
                </span>
            </div>
            <?php
endif; ?>

            <form action="<?= e(app_url('login'))?>" method="POST" class="mt-6 space-y-4" data-loading-form>
                <?= CsrfTokenManager::field()?>
                <input type="hidden" name="redirect_to" value="<?= e($redirectTarget)?>">
                <div>
                    <label for="email"
                        class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Email</label>
                    <input id="email" name="email" type="email" required value="<?= e((string)old('email'))?>"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </div>
                <div>
                    <label for="password"
                        class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </div>
                <label
                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                    <input type="checkbox" name="remember_me" value="1" <?=checked_if((bool)old('remember_me'))?>
                    class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-400 dark:border-slate-600">
                    Keep me signed in on this browser
                </label>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-glow transition hover:-translate-y-0.5">
                    <span data-button-label class="inline-flex items-center gap-2">
                        <?= icon('arrow-right-on-rectangle', 'h-4 w-4')?>Log in
                    </span>
                    <span data-button-loading class="hidden items-center gap-2">
                        <?= icon('arrow-path', 'h-4 w-4 animate-spin')?>Logging in...
                    </span>
                </button>
            </form>

            <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">
                No account yet? <a href="<?= e(app_url('signup?redirect=' . urlencode($redirectTarget)))?>"
                    class="font-semibold text-cyan-600 dark:text-cyan-300">Create one</a>
            </p>
        </section>
    </div>
</main>