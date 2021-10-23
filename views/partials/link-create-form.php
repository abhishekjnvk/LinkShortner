<?php
$returnTo = $returnTo ?? '/';
$submitLabel = $submitLabel ?? 'Generate short URL';
$formHeading = $formHeading ?? 'Launch in seconds';
$showHeaderBadge = $showHeaderBadge ?? true;
?>
<div
    class="rounded-[2rem] border border-white/80 bg-white/90 p-5 shadow-soft backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 sm:p-7">
    <?php if (!empty($result)): ?>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div
                class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">
                <?= icon('check-circle', 'mr-2 h-4 w-4')?> Link ready
            </div>
            <a href="<?= e(app_url())?>"
                class="text-xs font-bold text-blue-600 hover:underline dark:text-blue-400">Shorten another</a>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-slate-50/50 p-5 dark:border-slate-800 dark:bg-slate-950/50">
            <p class="text-[10px] font-bold tracking-[0.2em] text-slate-400">Your short link</p>
            <p class="mt-2 break-all font-mono text-xl font-bold text-slate-950 dark:text-white">
                <?= e($result['short_url'])?>
            </p>
        </div>

        <div class="flex flex-col items-center gap-6 py-2">
            <div
                class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div id="qr-code" data-qr-text="<?= e($result['short_url'])?>"
                    class="mx-auto flex min-h-[160px] min-w-[160px] items-center justify-center"></div>
            </div>
            <p class="text-[10px] font-bold tracking-[0.2em] text-slate-400">Scan QR Code</p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <button type="button" data-copy-text="<?= e($result['short_url'])?>"
                class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-4 text-sm font-bold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                <?= icon('copy', 'mr-2 h-4 w-4')?> Copy Link
            </button>
            <a href="<?= e($result['analytics_url'])?>"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                <?= icon('chart-bar', 'mr-2 h-4 w-4')?> Analytics
            </a>
        </div>

        <p class="text-center text-xs text-slate-400">Original: <span class="break-all italic">
                <?= e(truncate($result['original_url'], 50))?>
            </span></p>
    </div>
    <?php
else: ?>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-slate-400">Create a short link</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">
                <?= e($formHeading)?>
            </h2>
        </div>
        <?php if ($showHeaderBadge): ?>
        <div
            class="rounded-2xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-300">
            <?= is_authenticated() ? 'Owner access enabled' : 'No signup required'?>
        </div>
        <?php
    endif; ?>
    </div>

    <?php if (!empty($status)): ?>
    <div
        class="mt-5 rounded-2xl border px-4 py-3 text-sm font-medium <?= $status['type'] === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200'?>">
        <span class="inline-flex items-center gap-2">
            <?= $status['type'] === 'success' ? icon('check-circle', 'h-5 w-5') : icon('exclamation-triangle', 'h-5 w-5')?>
            <?= e($status['message'])?>
        </span>
    </div>
    <?php
    endif; ?>

    <form action="<?= e(app_url('shorten'))?>" method="POST" class="mt-6 space-y-4" data-loading-form>
        <?= CsrfTokenManager::field()?>
        <input type="hidden" name="return_to" value="<?= e($returnTo)?>">

        <div>
            <label for="original_url" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Long
                URL</label>
            <input id="original_url" name="original_url" type="url" required
                placeholder="https://example.com/your/very/long/link" value="<?= e((string)old('original_url'))?>"
                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-900 shadow-inner shadow-slate-100 outline-none transition focus:border-blue-400 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="custom_alias"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Custom alias</label>
                <input id="custom_alias" name="custom_alias" type="text" placeholder="launch2026"
                    value="<?= e((string)old('custom_alias'))?>"
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>
            <div>
                <label for="expiration_preset"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Expiration</label>
                <select id="expiration_preset" name="expiration_preset" data-expiration-preset
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <option value="never" <?=selected_if((string)old('expiration_preset', 'never' ), 'never' )?>>Never
                        expires</option>
                    <option value="1_day" <?=selected_if((string)old('expiration_preset'), '1_day' )?>>1 day</option>
                    <option value="7_days" <?=selected_if((string)old('expiration_preset'), '7_days' )?>>7 days
                    </option>
                    <option value="30_days" <?=selected_if((string)old('expiration_preset'), '30_days' )?>>30 days
                    </option>
                    <option value="custom" <?=selected_if((string)old('expiration_preset'), 'custom' )?>>Custom Date
                    </option>
                </select>
            </div>
        </div>

        <div data-expiration-custom-wrapper class="<?= old('expiration_preset') === 'custom' ? '' : 'hidden'?>">
            <label for="expires_at_custom"
                class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Custom expiration date and
                time</label>
            <input id="expires_at_custom" name="expires_at_custom" type="datetime-local"
                value="<?= e((string)old('expires_at_custom'))?>"
                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>

        <button type="submit"
            class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-sky-500 px-6 py-4 text-sm font-bold text-white shadow-glow transition duration-200 hover:-translate-y-0.5 hover:shadow-soft focus:outline-none focus:ring-4 focus:ring-blue-300">
            <span data-button-label class="inline-flex items-center gap-2">
                <?= icon('sparkles', 'h-4 w-4')?>
                <?= e($submitLabel)?>
                <span class="transition group-hover:translate-x-1">→</span>
            </span>
            <span data-button-loading class="hidden items-center gap-2">
                <?= icon('arrow-path', 'h-4 w-4 animate-spin')?>
                Working...
            </span>
        </button>
    </form>
    <?php
endif; ?>
</div>