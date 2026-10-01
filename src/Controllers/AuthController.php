<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetRepository;

class AuthController
{
    private AuthService $authService;
    private UserRepository $userRepo;
    private PasswordResetRepository $passwordResetRepo;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userRepo = new UserRepository();
        $this->passwordResetRepo = new PasswordResetRepository();
    }

    public function showLogin(Request $request): Response
    {
        return view('auth.login', [
            'title' => 'Sign In - SaaSify',
            'currentUser' => auth_user(),
            'currentTenant' => current_tenant(),
        ], 'auth');
    }

    public function login(Request $request): Response
    {
        $email = trim($request->input('email') ?? '');
        $password = $request->input('password') ?? '';

        Session::set('_old_input', ['email' => $email]);

        if ($this->authService->login($email, $password)) {
            Session::remove('_old_input');
            flash('success', 'Welcome back to your organization dashboard!');
            return redirect('/dashboard');
        }

        flash('error', 'Invalid email address or password.');
        return redirect('/login');
    }

    public function showRegister(Request $request): Response
    {
        return view('auth.register', [
            'title' => 'Create Organization Account - SaaSify',
            'currentUser' => auth_user(),
            'currentTenant' => current_tenant(),
        ], 'auth');
    }

    public function register(Request $request): Response
    {
        $companyName = trim($request->input('company_name') ?? '');
        $subdomain = strtolower(trim($request->input('subdomain') ?? ''));
        $name = trim($request->input('name') ?? '');
        $email = strtolower(trim($request->input('email') ?? ''));
        $password = $request->input('password') ?? '';
        $passwordConfirmation = $request->input('password_confirmation') ?? '';

        Session::set('_old_input', [
            'company_name' => $companyName,
            'subdomain' => $subdomain,
            'name' => $name,
            'email' => $email,
        ]);

        if (strlen($password) < 6) {
            flash('error', 'Password must be at least 6 characters long.');
            return redirect('/register');
        }

        if (!empty($passwordConfirmation) && $password !== $passwordConfirmation) {
            flash('error', 'Password confirmation does not match.');
            return redirect('/register');
        }

        try {
            $result = $this->authService->registerTenant($request->all());
            
            // Auto login after registration
            $this->authService->login($email, $password);
            
            Session::remove('_old_input');
            flash('success', 'Your organization account and 14-day free trial have been set up successfully!');
            return redirect('/dashboard');
        } catch (\InvalidArgumentException $e) {
            flash('error', $e->getMessage());
            return redirect('/register');
        } catch (\Throwable $e) {
            flash('error', 'An unexpected error occurred: ' . $e->getMessage());
            return redirect('/register');
        }
    }

    public function logout(Request $request): Response
    {
        $this->authService->logout();
        flash('success', 'You have been safely logged out.');
        return redirect('/');
    }

    public function showForgotPassword(Request $request): Response
    {
        return view('auth.forgot_password', [
            'title' => 'Reset Password - SaaSify',
        ], 'auth');
    }

    public function sendPasswordReset(Request $request): Response
    {
        $email = strtolower(trim($request->input('email') ?? ''));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            return redirect('/forgot-password');
        }

        $user = $this->userRepo->findByEmail($email);
        if ($user) {
            $token = $this->passwordResetRepo->createToken($email);
            $resetLink = app_url('/reset-password/' . $token);
            flash('reset_link', $resetLink);
            flash('success', 'A secure password reset link has been generated. Use the link below to set a new password.');
        } else {
            // Timing-safe generic response
            flash('success', 'If an organization account exists for that email, a password reset link has been dispatched.');
        }

        return redirect('/forgot-password');
    }

    public function showResetPassword(Request $request, string $token): Response
    {
        $reset = $this->passwordResetRepo->findByToken($token);
        if (!$reset) {
            flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            return redirect('/forgot-password');
        }

        return view('auth.reset_password', [
            'title' => 'Set New Password - SaaSify',
            'token' => $token,
            'email' => $reset['email'],
        ], 'auth');
    }

    public function resetPassword(Request $request, string $token): Response
    {
        $reset = $this->passwordResetRepo->findByToken($token);
        if (!$reset) {
            flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            return redirect('/forgot-password');
        }

        $password = $request->input('password') ?? '';
        $passwordConfirmation = $request->input('password_confirmation') ?? '';

        if (strlen($password) < 6) {
            flash('error', 'Password must be at least 6 characters long.');
            return redirect('/reset-password/' . $token);
        }

        if ($password !== $passwordConfirmation) {
            flash('error', 'Password confirmation does not match.');
            return redirect('/reset-password/' . $token);
        }

        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $this->userRepo->updatePasswordByEmail($reset['email'], $newHash);
        $this->passwordResetRepo->deleteToken($token);

        flash('success', 'Your password has been reset successfully! You can now sign in with your new credentials.');
        return redirect('/login');
    }
}