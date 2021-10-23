<?php
$includeChartJs = true;
$pageScript = 'analytics.js';
$dashboardPayload = [
    'hasData' => $hasData,
    'timeline' => $timeline,
    'countryBreakdown' => $countryBreakdown,
    'deviceBreakdown' => $deviceBreakdown,
    'browserBreakdown' => $browserBreakdown,
    'dailyTrend' => $dailyTrend,
    'hourlyHeatmap' => $hourlyHeatmap,
];
?>
<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8" data-analytics-page>
    <div class="mb-5 rounded-2xl border border-white/70 bg-white/90 p-4 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Analytics for</p>
                <h1 class="text-xl font-bold text-slate-950 dark:text-white">/<?= e($urlRecord['alias'])?></h1>
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-300">
                From: <?= e($from ?: 'Start') ?>
                &bull;
                To: <?= e($to ?: 'Now') ?>
            </div>
        </div>
    </div>
    <div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside
            class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-emerald-500 font-extrabold text-white shadow-glow">
                    /</div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Analytics</p>
                    <p class="truncate text-lg font-bold">
                        <?= e($urlRecord['alias'])?>
                    </p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl bg-slate-950 p-4 text-white dark:bg-black">
                <p class="text-xs font-semibold text-cyan-300">Short URL</p>
                <p class="mt-2 block break-all text-sm text-white/90">
                    <?= e(app_url($urlRecord['alias']))?>
                </p>
                <p class="mt-4 text-xs font-semibold text-cyan-300">Destination</p>
                <p class="mt-2 break-all text-sm text-slate-300">
                    <?= e($urlRecord['original_url'])?>
                </p>
            </div>



            <div
                class="mt-6 space-y-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Expires</span>
                    <span class="font-semibold text-slate-950 dark:text-white">
                        <?= e($urlRecord['expires_at'] ? format_datetime($urlRecord['expires_at']) : 'Never')?>
                    </span>
                </div>
            </div>

            <div class="mt-6 grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-2xl border border-white/70 bg-white/90 p-4 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <?= icon('cursor-click', 'h-4 w-4')?>
                        <span>Total clicks</span>
                    </div>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?= e(number_format((int)$stats['total_clicks'])) ?></p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/90 p-4 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <?= icon('users', 'h-4 w-4')?>
                        <span>Unique clicks</span>
                    </div>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?= e(number_format((int)$stats['unique_clicks'])) ?></p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/90 p-4 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <?= icon('globe-alt', 'h-4 w-4')?>
                        <span>Countries</span>
                    </div>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?= e(number_format((int)$stats['countries_reached'])) ?></p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/90 p-4 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <?= icon('shield-check', 'h-4 w-4')?>
                        <span>Bots</span>
                    </div>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?= e(number_format((int)$stats['bot_clicks'])) ?></p>
                </div>
            </div>
        </aside>

        <section class="space-y-6">
            <div id="overview"
                class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Dashboard
                            overview</p>
                        <h1 class="mt-2  font-extrabold text-slate-950 dark:text-white">Analytics for /
                            <?= e($urlRecord['alias'])?>
                        </h1>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <?php if (!empty($isOwnerView)): ?>
                        <a href="<?= e(app_url('dashboard'))?>"
                            class="inline-flex items-center rounded-full border border-cyan-200 bg-cyan-50 px-5 py-3 text-sm font-semibold text-cyan-700 dark:border-cyan-900/60 dark:bg-cyan-950/25 dark:text-cyan-200">
                            <?= icon('shield-check', 'mr-2 h-4 w-4')?>Owner access
                        </a>
                        <?php
endif; ?>
                        <a href="<?= e(app_url('analytics/' . $urlRecord['alias'] . '/export' . query_string_without([])))?>"
                            class="inline-flex items-center rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:shadow-soft dark:border-slate-700 dark:text-slate-100">
                            <?= icon('chart-bar', 'mr-2 h-4 w-4')?>Export CSV
                        </a>
                        <a href="<?= e(app_url($urlRecord['alias']))?>" target="_blank" rel="noopener"
                            class="inline-flex items-center rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-glow transition hover:-translate-y-0.5">
                            <?= icon('link', 'mr-2 h-4 w-4')?>Open short URL
                        </a>
                    </div>
                </div>

                <form method="GET" action="<?= e(app_url('analytics/' . $urlRecord['alias']))?>"
                    class="mt-6 grid gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950 md:grid-cols-[1fr_1fr_auto_auto]"
                    data-loading-form>
                    <div>
                        <label for="from"
                            class="mb-2 block text-xs font-semibold tracking-[0.2em] text-slate-400">From</label>
                        <input id="from" name="from" type="date" value="<?= e((string)$from)?>"
                            class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label for="to"
                            class="mb-2 block text-xs font-semibold tracking-[0.2em] text-slate-400">To</label>
                        <input id="to" name="to" type="date" value="<?= e((string)$to)?>"
                            class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>
                    <button type="submit"
                        class="mt-auto inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                        <span data-button-label class="inline-flex items-center gap-2">
                            <?= icon('chart-bar', 'h-4 w-4')?>Apply filter
                        </span>
                        <span data-button-loading class="hidden items-center gap-2">
                            <?= icon('arrow-path', 'h-4 w-4 animate-spin')?>Loading...
                        </span>
                    </button>
                    <a href="<?= e(app_url('analytics/' . $urlRecord['alias']))?>"
                        class="mt-auto inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 dark:border-slate-700 dark:text-slate-100">Reset</a>
                </form>

            </div>

            <?php if (!empty($hasData)): ?>
            <div id="referrers" class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <div
                    class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Referrer
                                sources</p>
                            <h2 class="mt-2 text-xl font-bold">Top referrers</h2>
                        </div>
                    </div>
                    <div class="mt-5 overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-950">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Referrer</th>
                                    <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Domain</th>
                                    <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Clicks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-900">
                                <?php foreach ($topReferrers as $referrer): ?>
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">
                                        <?= e($referrer['label'])?>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                        <?= e($referrer['domain'])?>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                        <?= e(number_format((int)$referrer['clicks']))?>
                                    </td>
                                </tr>
                                <?php
endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <div>
                        <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Top
                            locations</p>
                        <h2 class="mt-2 text-xl font-bold">Cities and countries</h2>
                    </div>
                    <div class="mt-5 space-y-3">
                        <?php foreach ($topLocations as $location): ?>
                        <div
                            class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950">
                            <div>
                                <p class="font-semibold text-slate-950 dark:text-white">
                                    <?= e(($location['city'] ?: 'Unknown') . ', ' . ($location['country_name'] ?: 'Unknown'))?>
                                </p>
                            </div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                <?= e(number_format((int)$location['clicks']))?>
                            </p>
                        </div>
                        <?php
endforeach; ?>
                    </div>
                </div>
            </div>

            <div id="visitors"
                class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Recent visitors</p>
                        <h2 class="mt-2 text-xl font-bold">Latest click activity</h2>
                    </div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                        Showing <?= e(min($recentVisitorsPage * $recentVisitorsPerPage, $recentVisitorsTotal)) ?> of <?= e($recentVisitorsTotal) ?> activity clicks
                    </div>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-950">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Time</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Location</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Device</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Browser</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Source</th>
                                <th class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400">Flags</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-900">
                            <?php foreach ($recentVisitors as $visitor): ?>
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">
                                    <?= e(format_datetime($visitor['clicked_at']))?>
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                    <?= e(($visitor['city'] ?: 'Unknown') . ', ' . ($visitor['country_name'] ?: 'Unknown'))?>
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><span
                                        class="inline-flex items-center gap-2">
                                        <?= icon(device_icon_name($visitor['device_name']), 'h-4 w-4')?>
                                        <?= e(($visitor['device_name'] ?: 'Unknown') . ' / ' . ($visitor['operating_system'] ?: 'Unknown'))?>
                                    </span></td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><span
                                        class="inline-flex items-center gap-2">
                                        <?= icon(browser_icon_name($visitor['browser_name']), 'h-4 w-4')?>
                                        <?= e($visitor['browser_name'] ?: 'Unknown')?>
                                    </span></td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                    <?= e($visitor['referrer_label'] ?: 'Direct / Unknown')?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            <?=(int)$visitor['is_unique'] === 1 ? 'Unique' : 'Returning'?>
                                        </span>
                                        <?php if ((int)$visitor['is_bot'] === 1): ?>
                                        <span
                                            class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-950/40 dark:text-rose-200">Bot</span>
                                        <?php
    endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                    $totalPages = (int) ceil($recentVisitorsTotal / $recentVisitorsPerPage);
                    if ($totalPages > 1):
                        $baseQuery = query_string_without(['page']);
                        $separator = $baseQuery === '' ? '?' : '&';
                        $prevPage = max(1, $recentVisitorsPage - 1);
                        $nextPage = min($totalPages, $recentVisitorsPage + 1);
                        $prevDisabled = $recentVisitorsPage <= 1;
                        $nextDisabled = $recentVisitorsPage >= $totalPages;
                ?>
                <div class="mt-4 flex items-center justify-between text-sm text-slate-700 dark:text-slate-300">
                    <div>
                        Page <?= e($recentVisitorsPage) ?> of <?= e($totalPages) ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="<?= e(app_url('analytics/' . $urlRecord['alias'] . $baseQuery . $separator . 'page=' . $prevPage)) ?>"
                            class="rounded-lg border px-3 py-1 font-semibold <?= $prevDisabled ? 'opacity-40 pointer-events-none' : 'hover:bg-slate-100 dark:hover:bg-slate-800' ?>">&larr; Prev</a>
                        <a href="<?= e(app_url('analytics/' . $urlRecord['alias'] . $baseQuery . $separator . 'page=' . $nextPage)) ?>"
                            class="rounded-lg border px-3 py-1 font-semibold <?= $nextDisabled ? 'opacity-40 pointer-events-none' : 'hover:bg-slate-100 dark:hover:bg-slate-800' ?>">Next &rarr;</a>
                    </div>
                </div>
                <?php endif; ?>
                    </div>

            <div id="timeline"
                class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-300">Click activity</p>
                        <h2 class="mt-2 text-xl font-bold">Timeline</h2>
                    </div>
                </div>
                <div class="mt-6 h-[350px]">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Devices</h2>
                    <div class="mt-6 h-[250px]">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
                <div class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Browsers</h2>
                    <div class="mt-6 h-[250px]">
                        <canvas id="browserChart"></canvas>
                    </div>
                </div>
            </div>

            <div id="heatmap"
                class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-cyan-600 dark:text-cyan-300">Hourly
                            activity heatmap</p>
                        <h2 class="mt-2 text-xl font-bold">When visitors are most active</h2>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Darker cells indicate more clicks.</div>
                </div>
                <div id="heatmapGrid" class="mt-6"></div>
            </div>

            <script id="analyticsData" type="application/json">
                <?= json_encode($dashboardPayload) ?>
            </script>
            <?php else: ?>
            <div class="rounded-[2rem] border border-yellow-300 bg-yellow-50 p-6 text-yellow-800 dark:border-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-100">
                <h2 class="text-xl font-bold">No analytics data yet</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">This link has not received any clicks in the selected date range. Once clicks start coming in, charts and visitor tables will appear here.</p>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Try removing date filters or share the link to generate traffic.</p>
            </div>
            <?php endif; ?>
        </section>
    </div>
</main>

