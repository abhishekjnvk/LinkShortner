<?php
$activePage = 'dashboard';
$includeQrJs = !empty($result);
?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <section
        class="rounded-[2rem] border border-white/80 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-300">Account
                    dashboard</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">Your links, analytics, and
                    ownership controls</h1>
                <p class="mt-3 max-w-3xl text-sm text-slate-500 dark:text-slate-400">Links you create while logged in
                    are automatically tied to your account. You can open analytics without public-password prompts, edit
                    aliases and expirations, and delete links you own.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 px-5 py-4 text-white dark:bg-black">
                <p class="text-xs font-semibold text-blue-300">Signed in as</p>
                <p class="mt-2 text-lg font-bold">
                    <?= e($user['email'])?>
                </p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-500 dark:text-slate-400">
            <span class="rounded-full bg-blue-50 px-4 py-2 text-blue-700 dark:bg-blue-950/40 dark:text-blue-200">Owned
                links:
                <?= e(number_format($summary['link_count']))?>
            </span>
            <span class="rounded-full bg-slate-100 px-4 py-2 dark:bg-slate-800">Total clicks:
                <?= e(number_format($summary['total_clicks']))?>
            </span>
            <span class="rounded-full bg-slate-100 px-4 py-2 dark:bg-slate-800">Unique clicks:
                <?= e(number_format($summary['unique_clicks']))?>
            </span>
            <span class="rounded-full bg-slate-100 px-4 py-2 dark:bg-slate-800">Scheduled expirations:
                <?= e(number_format($summary['scheduled_expirations']))?>
            </span>
        </div>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-1">

        <div
            class="rounded-[2rem] border border-white/80 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-300">My
                        links</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Manage everything you own</h2>
                </div>
                <div
                    class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    Owner-only controls</div>
            </div>

            <?php if (!empty($result)): ?>
            <div
                class="mt-5 rounded-3xl border border-blue-200 bg-blue-50/80 p-5 dark:border-blue-900/60 dark:bg-blue-950/25">
                <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_180px] lg:items-center">
                    <div>
                        <p class="text-xs font-bold tracking-[0.2em] text-blue-700 dark:text-blue-200">Latest
                            owned link</p>
                        <div
                            class="mt-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-950">
                            <p class="break-all font-mono text-base font-bold text-slate-950 dark:text-white">
                                <?= e($result['short_url'])?>
                            </p>
                        </div>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            <?= e($result['original_url'])?>
                        </p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" data-copy-text="<?= e($result['short_url'])?>"
                                class="rounded-full bg-slate-950 px-4 py-3 text-sm font-semibold text-white dark:bg-white dark:text-slate-950">
                                <?= icon('copy', 'mr-2 h-4 w-4')?>Copy
                            </button>
                            <a href="<?= e($result['analytics_url'])?>"
                                class="w-40 rounded-full border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-100">
                                <?= icon('chart-bar', 'mr-2 h-4 w-4')?>
                                Analytics
                            </a>
                        </div>
                    </div>
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-4 text-center shadow-sm dark:border-slate-700 dark:bg-slate-950">
                        <div id="qr-code" data-qr-text="<?= e($result['short_url'])?>"
                            class="mx-auto flex min-h-[132px] min-w-[132px] items-center justify-center"></div>
                        <p class="mt-3 text-xs font-semibold tracking-[0.2em] text-slate-400">QR code</p>
                    </div>
                </div>
            </div>
            <?php
endif; ?>

            <div class="mt-6 divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($links as $link): ?>
                <div class="group relative rounded-3xl px-4 py-6 transition-all duration-300 first:pt-0 last:pb-0 hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-3">
                                <p class="text-lg font-bold text-slate-950 transition-colors group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                    <?= e(app_url($link['alias']))?>
                                </p>
                                <button type="button" data-copy-text="<?= e(app_url($link['alias']))?>"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-blue-300 hover:text-blue-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-500 dark:hover:border-blue-800 dark:hover:text-blue-400">
                                    <?= icon('copy', 'h-4 w-4')?>
                                </button>
                            </div>
                            <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                                <?= e($link['original_url'])?>
                            </p>
                            <div class="mt-4 flex flex-wrap gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <?= icon('chart-bar', 'h-3.5 w-3.5 text-blue-500')?>
                                    <?= e(number_format((int)$link['total_clicks']))?> clicks
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <?= icon('users', 'h-3.5 w-3.5 text-sky-500')?>
                                    <?= e(number_format((int)$link['unique_clicks']))?> unique
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <?= icon('calendar', 'h-3.5 w-3.5 text-indigo-500')?>
                                    Expires: <?= e($link['expires_at'] ? format_datetime($link['expires_at'], 'd M Y') : 'Never')?>
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 lg:pt-1">
                            <a href="<?= e(app_url('analytics/' . $link['alias']))?>"
                                class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                                <?= icon('chart-bar', 'mr-2 h-4 w-4')?>
                                Analytics
                            </a>
                            <a href="<?= e(app_url('dashboard/link/' . $link['alias'] . '/edit'))?>"
                                class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                                <?= icon('pencil-square', 'mr-2 h-4 w-4')?>Edit
                            </a>
                            <form action="<?= e(app_url('dashboard/link/' . $link['alias'] . '/delete'))?>"
                                method="POST" onsubmit="return confirm('Delete this link permanently?');">
                                <?= CsrfTokenManager::field()?>
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 transition-all hover:-translate-y-0.5 hover:bg-rose-100 dark:bg-rose-950/30 dark:text-rose-400 dark:hover:bg-rose-950/50">
                                    <?= icon('trash', 'mr-2 h-4 w-4')?>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
endforeach; ?>

                <?php if (empty($links)): ?>
                <div
                    class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 py-40 text-center dark:border-slate-700 dark:bg-slate-950">
                    <p class="text-lg font-bold text-slate-950 dark:text-white">No owned links yet</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Create your first short URL from homepage.</p>
                </div>
                <?php
endif; ?>
            </div>
            <?php if ($pagination['totalPages'] > 1): ?>
            <div class="mt-8 flex items-center justify-between border-t border-slate-200 pt-6 dark:border-slate-800">
                <div class="hidden sm:block">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Showing <span
                            class="font-semibold text-slate-900 dark:text-white"><?= ($pagination['currentPage'] - 1) * $pagination['perPage'] + 1?></span>
                        to <span
                            class="font-semibold text-slate-900 dark:text-white"><?= min($pagination['currentPage'] * $pagination['perPage'], $pagination['totalItems'])?></span>
                        of <span
                            class="font-semibold text-slate-900 dark:text-white"><?= $pagination['totalItems']?></span>
                        results
                    </p>
                </div>
                <div class="flex flex-1 justify-between gap-3 sm:justify-end">
                    <?php if ($pagination['currentPage'] > 1): ?>
                    <a href="?page=<?= $pagination['currentPage'] - 1?>"
                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        <?= icon('chevron-left', 'mr-2 h-4 w-4')?> Previous
                    </a>
                    <?php
    else: ?>
                    <span
                        class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-700">
                        <?= icon('chevron-left', 'mr-2 h-4 w-4')?> Previous
                    </span>
                    <?php
    endif; ?>

                    <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                    <a href="?page=<?= $pagination['currentPage'] + 1?>"
                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        Next <?= icon('chevron-right', 'ml-2 h-4 w-4')?>
                    </a>
                    <?php
    else: ?>
                    <span
                        class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-700">
                        Next <?= icon('chevron-right', 'ml-2 h-4 w-4')?>
                    </span>
                    <?php
    endif; ?>
                </div>
            </div>
            <?php
endif; ?>
        </div>
    </section>
</main>