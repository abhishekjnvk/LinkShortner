<?php $activePage = 'dashboard'; ?>
<?php include __DIR__ . '/../partials/topbar.php'; ?>

<main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <section
        class="rounded-[2rem] border border-white/80 bg-white/90 p-8 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Edit owned link</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">Update /
                    <?= e($urlRecord['alias'])?>
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Change the alias or expiration schedule.
                    Analytics for owned links are private and only accessible by you.</p>
            </div>
            <a href="<?= e(app_url('dashboard'))?>"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-slate-700 dark:text-slate-100">
                <?= icon('arrow-right', 'h-4 w-4 rotate-180')?>
                Back to dashboard
            </a>
        </div>

        <?php if ($status): ?>
        <div
            class="mt-5 inline-flex w-full items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium <?= $status['type'] === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200'?>">
            <?= icon($status['type'] === 'success' ? 'check-circle' : 'exclamation-triangle', 'mt-0.5 h-5 w-5 shrink-0')?>
            <span>
                <?= e($status['message'])?>
            </span>
        </div>
        <?php
endif; ?>

        <form action="<?= e(app_url('dashboard/link/' . $urlRecord['alias'] . '/update'))?>" method="POST"
            class="mt-6 space-y-4" data-loading-form>
            <?= CsrfTokenManager::field()?>
            <div>
                <label for="alias"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Alias</label>
                <input id="alias" name="alias" type="text" required
                    value="<?= e((string)old('alias', $urlRecord['alias']))?>"
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>

            <div>
                <label for="expiration_preset"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Expiration date</label>
                <select id="expiration_preset" name="expiration_preset" data-expiration-preset
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <option value="never" <?= selected_if((string)$expirationPreset, 'never')?>>Never expires</option>
                    <option value="1_day" <?= selected_if((string)$expirationPreset, '1_day')?>>After 1 day</option>
                    <option value="7_days" <?= selected_if((string)$expirationPreset, '7_days')?>>After 7 days
                    </option>
                    <option value="30_days" <?= selected_if((string)$expirationPreset, '30_days')?>>After 30 days
                    </option>
                    <option value="90_days" <?= selected_if((string)$expirationPreset, '90_days')?>>After 90 days
                    </option>
                    <option value="custom" <?= selected_if((string)$expirationPreset, 'custom')?>>Custom date and time
                    </option>
                </select>
            </div>

            <div data-expiration-custom-wrapper class="<?= $expirationPreset === 'custom' ? '' : 'hidden'?>">
                <label for="expires_at_custom"
                    class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Custom expiration date
                    and time</label>
                <input id="expires_at_custom" name="expires_at_custom" type="datetime-local"
                    value="<?= e((string)$expiresAtCustom)?>"
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>


            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="inline-flex items-center rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-glow transition hover:-translate-y-0.5">
                    <span data-button-label class="inline-flex items-center gap-2">
                        <?= icon('pencil-square', 'h-4 w-4')?>
                        Save changes
                    </span>
                    <span data-button-loading class="hidden items-center gap-2">
                        <?= icon('arrow-path', 'h-4 w-4 animate-spin')?>
                        Saving...
                    </span>
                </button>
                <a href="<?= e(app_url('analytics/' . $urlRecord['alias']))?>"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-slate-700 dark:text-slate-100">
                    <?= icon('chart-bar', 'h-4 w-4')?>
                    View analytics
                </a>
            </div>
        </form>
    </section>
</main>