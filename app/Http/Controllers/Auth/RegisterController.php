<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use App\Services\Auth\EmailVerificationService;
use App\Services\Registration\PendingRegistrationEmailService;
use App\Services\Registration\PendingRegistrationService;
use App\Services\Registration\StudentEmailValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AdminDueService $dues,
        private readonly StudentEmailValidator $emailValidator,
        private readonly PendingRegistrationService $pendingService,
        private readonly PendingRegistrationEmailService $pendingEmailService,
    ) {
    }

    /**
     * Show the student registration form.
     */
    public function create(): View
    {
        return view('auth.register', [
            'schoolDomain' => StudentEmailValidator::getSchoolDomain(),
            'classPrefixes' => StudentEmailValidator::getValidPrefixes(),
        ]);
    }

    /**
     * Check if a username is available.
     * 
     * This performs real-time validation to prevent duplicate username errors.
     */
    public function checkUsername(Request $request): JsonResponse
    {
        $username = trim($request->input('username', ''));

        // Validate basic format
        if (strlen($username) < 3) {
            return response()->json([
                'available' => false,
                'message' => 'Username must be at least 3 characters.',
            ]);
        }

        if (strlen($username) > 50) {
            return response()->json([
                'available' => false,
                'message' => 'Username must be 50 characters or less.',
            ]);
        }

        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
            return response()->json([
                'available' => false,
                'message' => 'Username can only contain letters, numbers, dashes, and underscores.',
            ]);
        }

        // Check in users table
        $existsInUsers = User::where('username', $username)->exists();

        if ($existsInUsers) {
            return response()->json([
                'available' => false,
                'message' => 'This username is already taken.',
            ]);
        }

        // Check in pending_registrations table (only pending ones with verified email)
        $existsInPending = PendingRegistration::where('username', $username)
            ->pending()
            ->whereNotNull('email_verified_at')
            ->exists();

        if ($existsInPending) {
            return response()->json([
                'available' => false,
                'message' => 'This username is already reserved by a pending registration.',
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Username is available!',
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * This method implements dual-flow registration:
     * 1. School email (st.umat.edu.gh with matching prefix): Standard verification flow
     * 2. Non-school email or mismatched prefix: Email verification first, then manual admin review
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $email = $data['email'];
        $class = $data['class'];

        // Check for email/class mismatch if using school email
        if ($this->emailValidator->isSchoolEmail($email)) {
            $mismatchMessage = $this->emailValidator->getMismatchMessage($email, $class);

            if ($mismatchMessage) {
                return redirect()->route('auth.register')
                    ->withErrors(['email' => $mismatchMessage])
                    ->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Check for existing verified users
        $existingByEmail = User::where('email', $email)->first();
        $existingByIndex = User::where('index_number', $data['index_number'])->first();

        $verifiedUser = null;

        if ($existingByEmail && $existingByEmail->email_verified_at !== null) {
            $verifiedUser = $existingByEmail;
        }

        if (! $verifiedUser && $existingByIndex && $existingByIndex->email_verified_at !== null) {
            $verifiedUser = $existingByIndex;
        }

        if ($verifiedUser) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => __('An active account already exists for this email or reference number. Please log in or reset your password.'),
                ]);
        }

        // Check for existing pending registrations (with verified email only)
        $existingPending = PendingRegistration::where(function ($query) use ($email, $data) {
                $query->where('email', $email)
                    ->orWhere('index_number', $data['index_number']);
            })
            ->pending()
            ->whereNotNull('email_verified_at')
            ->first();

        if ($existingPending) {
            return redirect()->route('auth.register')
                ->withErrors([
                    'email' => __('A registration request with this email or reference number is already pending review. Please wait for admin approval.'),
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Determine registration flow based on email
        if ($this->emailValidator->canAutoVerify($email, $class)) {
            // Standard flow: School email with matching prefix
            return $this->handleStandardRegistration($request, $data, $fullName);
        } else {
            // Manual verification flow: Non-school email - verify email first, then admin review
            return $this->handlePendingRegistration($request, $data, $fullName);
        }
    }

    /**
     * Handle standard registration with email verification.
     */
    private function handleStandardRegistration(
        RegisterRequest $request,
        array $data,
        string $fullName
    ): RedirectResponse {
        // Check for unverified user with same email or index
        $existingByEmail = User::where('email', $data['email'])->first();
        $existingByIndex = User::where('index_number', $data['index_number'])->first();

        if ($existingByIndex && ! $existingByEmail && $existingByIndex->email_verified_at === null) {
            return redirect()->route('auth.register')
                ->withErrors([
                    'email' => __('An account for this reference number is already pending with a different email. Please use the original email address or contact the ACSES team for assistance.'),
                ])->withInput($request->except('password', 'password_confirmation'));
        }

        $user = $existingByEmail;

        if ($user) {
            $user->fullname = $fullName;
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->phone_number = $data['phone_number'] ?? null;
            $user->index_number = $data['index_number'];
            $user->class = $data['class'];
            $user->year = $data['year'];
            $user->role = 'student';
            $user->save();
        } else {
            $user = User::create([
                'fullname' => $fullName,
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'] ?? null,
                'index_number' => $data['index_number'],
                'class' => $data['class'],
                'year' => $data['year'],
                'role' => 'student',
            ]);
        }

        $this->dues->syncStudent($user);

        app(EmailVerificationService::class)->send($user);

        $request->session()->put('pending_verification', [
            'email' => $user->email,
            'guard' => 'student',
            'user_id' => $user->getAuthIdentifier(),
            'remember' => false,
        ]);

        return redirect()->route('auth.verify.notice')
            ->with('status', __('Registration successful. Please verify your email to activate your account.'));
    }

    /**
     * Handle pending registration that requires admin approval.
     * First sends email verification, only after which the registration goes to admin queue.
     */
    private function handlePendingRegistration(Request $request, array $data, string $fullName): RedirectResponse
    {
        // Check for existing unverified pending registration with same email
        $existingUnverified = PendingRegistration::where('email', $data['email'])
            ->whereNull('email_verified_at')
            ->pending()
            ->first();

        if ($existingUnverified) {
            // Resend verification to existing unverified registration
            $this->pendingEmailService->sendVerification($existingUnverified);

            $request->session()->put('pending_registration_verification', [
                'registration_id' => $existingUnverified->id,
                'email' => $existingUnverified->email,
            ]);

            return redirect()->route('auth.pending-registration.verify')
                ->with('status', __('A verification code has been resent to your email.'));
        }

        // Create new pending registration (without email_verified_at = needs verification)
        $registration = PendingRegistration::create([
            'fullname' => $fullName,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'index_number' => $data['index_number'],
            'class' => $data['class'],
            'year' => $data['year'],
            'status' => 'pending',
            // email_verified_at is NULL - will be set after OTP verification
        ]);

        // Send email verification OTP
        $this->pendingEmailService->sendVerification($registration);

        // Store in session for verification
        $request->session()->put('pending_registration_verification', [
            'registration_id' => $registration->id,
            'email' => $registration->email,
        ]);

        return redirect()->route('auth.pending-registration.verify')
            ->with('status', __('Please verify your email address to complete your registration.'));
    }
}

