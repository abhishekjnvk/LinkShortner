<?php

abstract class BaseController
{
    protected function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!is_file($viewPath)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        include __DIR__ . '/../views/layouts/' . $layout . '.php';
    }

    protected function redirect(string $path, int $status = 302): never
    {
        redirect_to($path, $status);
    }

    protected function requireAuth(): array
    {
        $user = current_user();
        if ($user) {
            return $user;
        }

        FlashBag::set('status', ['type' => 'error', 'message' => 'Please log in to access your dashboard.']);
        $this->redirect('login?redirect=' . urlencode(current_request_target()));
    }

    protected function abort(int $statusCode, string $view): never
    {
        http_response_code($statusCode);
        include __DIR__ . '/../views/' . $view . '.php';
        exit;
    }
}
