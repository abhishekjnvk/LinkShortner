<?php

class AuthManager
{
    private static ?self $instance = null;

    private ?array $user = null;

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function boot(): void
    {
        self::instance()->restoreUser();
    }

    public function user(): ?array
    {
        return $this->user;
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function id(): ?int
    {
        return $this->user ? (int) $this->user['id'] : null;
    }

    public function login(array $user, bool $rememberMe = false): void
    {
        session_regenerate_id(true);
        $_SESSION['auth_user_id'] = (int) $user['id'];
        $this->user = $user;

        if ($rememberMe) {
            $this->issueRememberCookie((int) $user['id']);
            return;
        }

        $this->deleteRememberCookieRecord();
        $this->clearRememberCookie();
    }

    public function logout(): void
    {
        $this->deleteRememberCookieRecord();
        $this->clearRememberCookie();

        unset($_SESSION['auth_user_id'], $_SESSION['analytics_access']);
        session_regenerate_id(true);
        $this->user = null;
    }

    private function restoreUser(): void
    {
        $userModel = new User();
        $sessionUserId = $_SESSION['auth_user_id'] ?? null;

        if ($sessionUserId) {
            $user = $userModel->findById((int) $sessionUserId);
            if ($user) {
                $this->user = $user;
                return;
            }

            unset($_SESSION['auth_user_id']);
        }

        $this->restoreFromRememberCookie();
    }

    private function restoreFromRememberCookie(): void
    {
        $cookieName = config('security.remember_cookie');
        $cookieValue = $_COOKIE[$cookieName] ?? '';

        if (!str_contains($cookieValue, ':')) {
            return;
        }

        [$selector, $validator] = explode(':', $cookieValue, 2);
        if ($selector === '' || $validator === '') {
            return;
        }

        $rememberTokenModel = new RememberToken();
        $token = $rememberTokenModel->findValidToken($selector);

        if (!$token || !hash_equals($token['token_hash'], hash('sha256', $validator))) {
            if ($selector !== '') {
                $rememberTokenModel->deleteBySelector($selector);
            }
            $this->clearRememberCookie();
            return;
        }

        $user = (new User())->findById((int) $token['user_id']);
        if (!$user) {
            $rememberTokenModel->deleteBySelector($selector);
            $this->clearRememberCookie();
            return;
        }

        $rememberTokenModel->deleteBySelector($selector);
        $this->login($user, true);
    }

    private function issueRememberCookie(int $userId): void
    {
        $rememberTokenModel = new RememberToken();
        $this->deleteRememberCookieRecord();

        $token = $rememberTokenModel->create($userId);
        setcookie(config('security.remember_cookie'), $token['cookie'], [
            'expires' => strtotime($token['expires_at']),
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function deleteRememberCookieRecord(): void
    {
        $cookieName = config('security.remember_cookie');
        $cookieValue = $_COOKIE[$cookieName] ?? '';
        if (!str_contains($cookieValue, ':')) {
            return;
        }

        [$selector] = explode(':', $cookieValue, 2);
        if ($selector !== '') {
            (new RememberToken())->deleteBySelector($selector);
        }
    }

    private function clearRememberCookie(): void
    {
        setcookie(config('security.remember_cookie'), '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
