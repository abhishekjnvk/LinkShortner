<?php

class DashboardController extends BaseController
{
    private Url $urlModel;
    private UrlSecurity $urlSecurity;
    private ExpirationManager $expirationManager;

    public function __construct()
    {
        $this->urlModel = new Url();
        $this->urlSecurity = new UrlSecurity();
        $this->expirationManager = new ExpirationManager();
    }

    public function index(): void
    {
        $user = $this->requireAuth();

        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $perPage = 10;
        $summary = $this->urlModel->userSummary((int)$user['id']);
        $totalPages = ceil($summary['link_count'] / $perPage);

        $this->render('dashboard/index', [
            'pageTitle' => 'My links',
            'status' => FlashBag::get('status'),
            'result' => FlashBag::get('short_result'),
            'user' => $user,
            'summary' => $summary,
            'links' => $this->urlModel->linksForUserPaginated((int)$user['id'], $page, $perPage),
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'totalItems' => $summary['link_count'],
            ],
        ]);

        clear_old_input();
    }

    public function edit(string $alias): void
    {
        $user = $this->requireAuth();
        $urlRecord = $this->urlModel->findOwnedByAlias($alias, (int)$user['id']);
        if (!$urlRecord) {
            $this->abort(404, 'errors/404');
        }

        $this->render('dashboard/edit', [
            'pageTitle' => 'Edit /' . $urlRecord['alias'],
            'status' => FlashBag::get('status'),
            'urlRecord' => $urlRecord,
            'expirationPreset' => old('expiration_preset', $this->expirationManager->presetFor($urlRecord['expires_at'])),
            'expiresAtCustom' => old('expires_at_custom', $urlRecord['expires_at'] ? date('Y-m-d\TH:i', strtotime($urlRecord['expires_at'])) : ''),
        ]);

        clear_old_input();
    }

    public function update(string $alias): never
    {
        $user = $this->requireAuth();
        $urlRecord = $this->urlModel->findOwnedByAlias($alias, (int)$user['id']);
        if (!$urlRecord) {
            $this->abort(404, 'errors/404');
        }

        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('dashboard/link/' . $alias . '/edit');
        }

        remember_input([
            'alias' => $_POST['alias'] ?? '',
            'expiration_preset' => $_POST['expiration_preset'] ?? 'never',
            'expires_at_custom' => $_POST['expires_at_custom'] ?? '',
        ]);

        try {
            $newAlias = $this->urlSecurity->normalizeAlias((string)($_POST['alias'] ?? ''));
            if ($newAlias === null) {
                throw new InvalidArgumentException('Alias is required when editing a link.');
            }

            if ($this->urlModel->aliasExistsExcludingId($newAlias, (int)$urlRecord['id'])) {
                throw new InvalidArgumentException('That alias is already in use.');
            }

            $expiresAt = $this->expirationManager->resolve(
                (string)($_POST['expiration_preset'] ?? 'never'),
                (string)($_POST['expires_at_custom'] ?? '')
            );

            $this->urlModel->updateOwnedLink((int)$urlRecord['id'], (int)$user['id'], [
                'alias' => $newAlias,
                'expires_at' => $expiresAt,
            ]);

            $cache = new UrlLookupCache();
            $cache->forget($alias);
            $updated = $this->urlModel->findByAlias($newAlias);
            if ($updated) {
                $cache->put($newAlias, $updated);
            }

            clear_old_input();
            FlashBag::set('status', ['type' => 'success', 'message' => 'Link updated successfully.']);
            $this->redirect('dashboard');
        }
        catch (Throwable $exception) {
            FlashBag::set('status', ['type' => 'error', 'message' => $exception->getMessage()]);
            $this->redirect('dashboard/link/' . $alias . '/edit');
        }
    }

    public function delete(string $alias): never
    {
        $user = $this->requireAuth();
        $urlRecord = $this->urlModel->findOwnedByAlias($alias, (int)$user['id']);
        if (!$urlRecord) {
            $this->abort(404, 'errors/404');
        }

        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('dashboard');
        }

        (new UrlLookupCache())->forget($alias);
        $this->urlModel->deleteOwnedLink((int)$urlRecord['id'], (int)$user['id']);
        FlashBag::set('status', ['type' => 'success', 'message' => 'Link deleted successfully.']);
        $this->redirect('dashboard');
    }
}