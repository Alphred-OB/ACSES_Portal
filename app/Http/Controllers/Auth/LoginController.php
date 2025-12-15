<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\PendingRegistration;
use App\Services\Auth\DeviceDetectionService;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\LoginOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginOtpService $loginOtpService,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly DeviceDetectionService $deviceDetectionService,
    ) {
    }

    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $remember = $request->boolean('remember');
        $guards = ['admin', 'student'];

        // First, check if there's a pending or rejected registration for this email
        $pendingRegistration = PendingRegistration::where('email', $credentials['email'])
            ->whereIn('status', ['pending', 'rejected'])
            ->latest()
            ->first();

        if ($pendingRegistration) {
            $message = match ($pendingRegistration->status) {
                'pending' => __('Your registration is still pending approval. Please wait for an administrator to review your application. You will receive an email once it has been processed.'),
                'rejected' => __('Your registration request was rejected. Reason: :reason Please submit a new registration application if you wish to try again.', [
                    'reason' => $pendingRegistration->rejection_reason ?? 'No specific reason provided.',
                ]),
                default => __('Your account access has been restricted. Please contact an administrator.'),
            };

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email');
        }

        foreach ($guards as $guard) {
            $provider = Auth::guard($guard)->getProvider();
            $credentialsWithRole = array_merge($credentials, ['role' => $guard]);
            $user = $provider->retrieveByCredentials($credentialsWithRole);

            if (! $user || ! $provider->validateCredentials($user, $credentials)) {
                continue;
            }

            if (method_exists($user, 'getAttribute')) {
                $role = $user->getAttribute('role');

                if ($role && $role !== $guard) {
                    continue;
                }
            }

            if (method_exists($user, 'getAttribute') && is_null($user->getAttribute('email_verified_at'))) {
                $this->emailVerificationService->send($user);
                $request->session()->put('pending_verification', [
                    'email' => $user->email,
                    'guard' => $guard,
                    'remember' => $remember,
                ]);

                return redirect()->route('auth.verify.notice')
                    ->withErrors(['verification' => __('Please verify your email before signing in. We just sent you a new verification link.')]);
            }

            // Check if this is a trusted device - if so, skip OTP
            if ($this->deviceDetectionService->isTrustedDevice($user, $request)) {
                // Update device last used timestamp
                $this->deviceDetectionService->touchDevice($user, $request);

                // Log the user in directly
                Auth::shouldUse($guard);
                Auth::guard($guard)->login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(match ($guard) {
                    'admin' => route('admin.dashboard'),
                    default => route('student.dashboard'),
                })->with('status', __('Welcome back!'));
            }

            // New device detected - require OTP verification
            $this->loginOtpService->send($user, $guard);

            $request->session()->put('pending_login_otp', [
                'user_id' => $user->getAuthIdentifier(),
                'guard' => $guard,
                'remember' => $remember,
                'email' => $user->email,
            ]);

            return redirect()->route('auth.login.otp')
                ->with('status', __('New device detected. We sent a verification code to your email.'));
        }

        return back()
            ->withErrors([
                'email' => __('auth.failed'),
            ])
            ->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): RedirectResponse
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        request()->session()->forget(['pending_login_otp']);

        return redirect()->route('login')
            ->with('status', __('You have been logged out.'));
    }
}
