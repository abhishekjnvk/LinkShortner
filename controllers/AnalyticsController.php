<?php

class AnalyticsController extends BaseController
{
    private Url $urlModel;
    private Click $clickModel;

    public function __construct()
    {
        $this->urlModel = new Url();
        $this->clickModel = new Click();
    }

    public function show(string $alias): void
    {
        $urlRecord = $this->urlModel->findByAlias($alias);
        if (!$urlRecord) {
            $this->abort(404, 'errors/404');
        }

        $isOwner = (int) ($urlRecord['user_id'] ?? 0) === (int) auth_user_id();
        $isGuestLink = $urlRecord['user_id'] === null;

        if (!$isOwner && !$isGuestLink) {
            $this->abort(403, 'errors/403');
            return;
        }

        $from = request_input('from');
        $to = request_input('to');
        $stats = $this->clickModel->stats((int) $urlRecord['id'], $from, $to);
        $hasData = (int) ($stats['total_clicks'] ?? 0) > 0;

        $perPage = 10;
        $page = max(1, (int) request_input('page', 1));
        $offset = ($page - 1) * $perPage;

        $recentVisitors = $this->clickModel->recentVisitors((int) $urlRecord['id'], $from, $to, $perPage, $offset);
        $recentVisitorsTotal = $this->clickModel->recentVisitorsCount((int) $urlRecord['id'], $from, $to);

        $this->render('analytics/show', [
            'pageTitle' => 'Analytics for /' . $urlRecord['alias'],
            'urlRecord' => $urlRecord,
            'from' => $from,
            'to' => $to,
            'stats' => $stats,
            'hasData' => $hasData,
            'timeline' => $this->clickModel->timeline((int) $urlRecord['id'], $from, $to),
            'countryBreakdown' => $this->clickModel->byCountry((int) $urlRecord['id'], $from, $to),
            'deviceBreakdown' => $this->clickModel->byDevice((int) $urlRecord['id'], $from, $to),
            'browserBreakdown' => $this->clickModel->byBrowser((int) $urlRecord['id'], $from, $to),
            'topReferrers' => $this->clickModel->topReferrers((int) $urlRecord['id'], $from, $to),
            'recentVisitors' => $recentVisitors,
            'recentVisitorsTotal' => $recentVisitorsTotal,
            'recentVisitorsPerPage' => $perPage,
            'recentVisitorsPage' => $page,
            'topLocations' => $this->clickModel->topLocations((int) $urlRecord['id'], $from, $to),
            'hourlyHeatmap' => $this->clickModel->hourlyHeatmap((int) $urlRecord['id'], $from, $to),
            'dailyTrend' => $this->clickModel->dailyTrend((int) $urlRecord['id']),
            'isOwnerView' => $isOwner,
        ]);
    }


    public function export(string $alias): never
    {
        $urlRecord = $this->urlModel->findByAlias($alias);
        if (!$urlRecord) {
            $this->abort(404, 'errors/404');
        }

        $isOwner = (int) ($urlRecord['user_id'] ?? 0) === (int) auth_user_id();
        $isGuestLink = $urlRecord['user_id'] === null;
 
        if (!$isOwner && !$isGuestLink) {
            $this->redirect('analytics/' . $alias);
        }

        $rows = $this->clickModel->exportRows((int) $urlRecord['id'], request_input('from'), request_input('to'));
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="analytics-' . $alias . '.csv"');

        $stream = fopen('php://output', 'wb');
        fputcsv($stream, [
            'Clicked At',
            'IP Address',
            'City',
            'Country',
            'Device',
            'Browser',
            'Operating System',
            'Language',
            'Timezone',
            'Screen Resolution',
            'Unique',
            'Bot',
            'Referrer',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'UTM Term',
            'UTM Content',
        ]);

        foreach ($rows as $row) {
            fputcsv($stream, [
                $row['clicked_at'],
                $row['ip_address'],
                $row['city'],
                $row['country_name'],
                $row['device_name'],
                $row['browser_name'],
                $row['operating_system'],
                $row['language'],
                $row['timezone'],
                $row['screen_resolution'],
                $row['is_unique'],
                $row['is_bot'],
                $row['referrer_label'],
                $row['utm_source'],
                $row['utm_medium'],
                $row['utm_campaign'],
                $row['utm_term'],
                $row['utm_content'],
            ]);
        }

        fclose($stream);
        exit;
    }

}
