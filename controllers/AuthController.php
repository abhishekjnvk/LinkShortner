<?php

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (is_authenticated()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/login', [
            'pageTitle' => 'Log in',
            'status' => FlashBag::get('status'),
            'redirectTarget' => sanitize_internal_path(request_input('redirect', '/dashboard'), '/dashboard'),
        ]);

        clear_old_input();
    }

    public function login(): never
    {
        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('login');
        }

        remember_input([
            'email' => $_POST['email'] ?? '',
            'remember_me' => $_POST['remember_me'] ?? '',
        ]);

        $redirectTo = sanitize_internal_path($_POST['redirect_to'] ?? '/dashboard', '/dashboard');
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'Invalid email or password.']);
            $this->redirect('login?redirect=' . urlencode($redirectTo));
        }

        auth()->login($user, isset($_POST['remember_me']));
        clear_old_input();
        FlashBag::set('status', ['type' => 'success', 'message' => 'Welcome back.']);
        $this->redirect(ltrim($redirectTo, '/'));
    }

    public function showSignup(): void
    {
        if (is_authenticated()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/signup', [
            'pageTitle' => 'Create account',
            'status' => FlashBag::get('status'),
            'redirectTarget' => sanitize_internal_path(request_input('redirect', '/dashboard'), '/dashboard'),
        ]);

        clear_old_input();
    }

    public function signup(): never
    {
        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('signup');
        }

        remember_input([
            'email' => $_POST['email'] ?? '',
        ]);

        $redirectTo = sanitize_internal_path($_POST['redirect_to'] ?? '/dashboard', '/dashboard');
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Please enter a valid email address.');
            }

            if (strlen($password) < 8) {
                throw new InvalidArgumentException('Password must be at least 8 characters long.');
            }

            $userModel = new User();
            if ($userModel->emailExists($email)) {
                throw new InvalidArgumentException('An account already exists for that email.');
            }

            $userId = $userModel->create($email, password_hash($password, PASSWORD_DEFAULT));
            $user = $userModel->findById($userId);
            auth()->login($user, false);

            clear_old_input();
            FlashBag::set('status', ['type' => 'success', 'message' => 'Account created successfully.']);
            $this->redirect(ltrim($redirectTo, '/'));
        } catch (Throwable $exception) {
            FlashBag::set('status', ['type' => 'error', 'message' => $exception->getMessage()]);
            $this->redirect('signup?redirect=' . urlencode($redirectTo));
        }
    }

    public function logout(): never
    {
        if (!CsrfTokenManager::verify($_POST['_token'] ?? null)) {
            FlashBag::set('status', ['type' => 'error', 'message' => 'The form session expired. Please try again.']);
            $this->redirect('/');
        }

        auth()->logout();
        FlashBag::set('status', ['type' => 'success', 'message' => 'You have been logged out.']);
        $this->redirect('/');
    }
}
