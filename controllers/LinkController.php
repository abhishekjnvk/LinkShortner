<?php

class LinkController extends BaseController
{
    private Url $urlModel;
    private Click $clickModel;
    private UrlSecurity $urlSecurity;
    private ExpirationManager $expirationManager;

    public function __construct()
    {
        $this->urlModel = new Url();
        $this->clickModel = new Click();
        $this->urlSecurity = new UrlSecurity();
        $this->expirationManager = new ExpirationManager();
    }

    public function create(): never
    {
        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('/');
        }

        remember_input([
            'original_url' => $_POST['original_url'] ?? '',
            'custom_alias' => $_POST['custom_alias'] ?? '',
            'expiration_preset' => $_POST['expiration_preset'] ?? 'never',
            'expires_at_custom' => $_POST['expires_at_custom'] ?? '',
        ]);

        try {
            (new RateLimiter())->ensureWithinLimit(current_user_ip());

            $originalUrl = $this->urlSecurity->normalizeDestination((string) ($_POST['original_url'] ?? ''));
            $alias = $this->urlSecurity->normalizeAlias($_POST['custom_alias'] ?? null);
            $redirectType = (int) config('app.redirect_status', 302);
            $expiresAt = $this->expirationManager->resolve(
                (string) ($_POST['expiration_preset'] ?? 'never'),
                (string) ($_POST['expires_at_custom'] ?? '')
            );
            $ownerId = auth_user_id();

            $returnTo = sanitize_internal_path($_POST['return_to'] ?? '/', is_authenticated() ? '/dashboard' : '/');

            $alias = $alias ?: $this->generateAlias();
            if ($this->urlModel->aliasExists($alias)) {
                throw new InvalidArgumentException('That alias is already in use.');
            }

            $urlId = $this->urlModel->create([
                'user_id' => $ownerId,
                'original_url' => $originalUrl,
                'alias' => $alias,
                'redirect_type' => $redirectType,
                'expires_at' => $expiresAt,
                'created_ip' => current_user_ip(),
            ]);

            $createdUrl = $this->urlModel->findByAlias($alias);
            (new UrlLookupCache())->put($alias, $createdUrl);
            clear_old_input();

            FlashBag::set('status', ['type' => 'success', 'message' => 'Short link created successfully.']);
            FlashBag::set('short_result', $this->resultPayload($createdUrl));
        } catch (RateLimitExceededException $exception) {
            FlashBag::set('status', [
                'type' => 'error',
                'message' => 'Too many link creations right now. Please wait ' . $exception->retryAfter() . ' seconds and try again.',
            ]);
        } catch (Throwable $exception) {
            FlashBag::set('status', ['type' => 'error', 'message' => $exception->getMessage()]);
        }

        $this->redirect(ltrim(sanitize_internal_path($_POST['return_to'] ?? '/', is_authenticated() ? '/dashboard' : '/'), '/'));
    }

    public function visit(string $alias): void
    {
        $limiter = new RateLimiter();
        $identity = (auth_user_id() ? 'user:' . auth_user_id() : 'ip:' . current_user_ip());
        $result = $limiter->hit($identity, 'visit');
        if (!$result['allowed']) {
            http_response_code(429);
            $this->render('errors/rate_limited', [
                'pageTitle' => 'Please slow down',
                'retryAfter' => (int) $result['retry_after'],
                'limit' => (int) $result['limit'],
                'isAuthenticated' => is_authenticated(),
            ]);
            return;
        }

        $cache = new UrlLookupCache();
        $urlRecord = $cache->get($alias);
        if (!$urlRecord) {
            $urlRecord = $this->urlModel->findByAlias($alias);
            if ($urlRecord) {
                $cache->put($alias, $urlRecord);
            }
        }

        if (!$urlRecord || !(int) $urlRecord['is_active']) {
            $this->abort(404, 'errors/404');
        }

        if ($this->urlModel->isExpired($urlRecord)) {
            $this->abort(410, 'errors/expired');
        }

        $logger = new AnalyticsLogger();
        $click = $logger->createClick($this->urlModel, $this->clickModel, $urlRecord);

        if ($click['is_bot']) {
            header('Location: ' . $click['target_url'], true, $click['redirect_type']);
            exit;
        }

        $this->render('home/redirect', [
            'pageTitle' => 'Redirecting...',
            'urlRecord' => $urlRecord,
            'trackingKey' => $click['tracking_key'],
            'targetUrl' => $click['target_url'],
            'redirectType' => $click['redirect_type'],
        ], 'blank');
    }

    public function track(string $trackingKey): never
    {
        header('Content-Type: application/json');

        try {
            $payload = json_decode(file_get_contents('php://input') ?: '{}', true, 512, JSON_THROW_ON_ERROR);
            (new AnalyticsLogger())->updateClientMetadata($this->clickModel, $trackingKey, $payload);
            echo json_encode(['status' => 'ok'], JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            http_response_code(422);
            echo json_encode(['status' => 'error'], JSON_THROW_ON_ERROR);
        }

        exit;
    }

    private function generateAlias(): string
    {
        do {
            $alias = substr(strtolower(rtrim(strtr(base64_encode(random_bytes(6)), '+/', 'xy'), '=')), 0, 8);
        } while ($this->urlModel->aliasExists($alias));

        return $alias;
    }

    private function resultPayload(array $urlRecord): array
    {
        return [
            'original_url' => $urlRecord['original_url'],
            'alias' => $urlRecord['alias'],
            'short_url' => app_url($urlRecord['alias']),
            'analytics_url' => app_url('analytics/' . $urlRecord['alias']),
            'expires_at' => $urlRecord['expires_at'],
            'owned_by_user' => (int) ($urlRecord['user_id'] ?? 0) === (int) auth_user_id(),
        ];
    }
}
