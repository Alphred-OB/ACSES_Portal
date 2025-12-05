<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use App\Services\Auth\EmailVerificationService;
use App\Services\Registration\PendingRegistrationService;
use App\Services\Registration\StudentEmailValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AdminDueService $dues,
        private readonly StudentEmailValidator $emailValidator,
        private readonly PendingRegistrationService $pendingService,
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
     * Handle an incoming registration request.
     *
     * This method implements dual-flow registration:
     * 1. School email (st.umat.edu.gh with matching prefix): Standard verification flow
     * 2. Non-school email or mismatched prefix: Manual admin review via pending registration
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
                    'email' => __('An active account already exists for this email or index number. Please log in or reset your password.'),
                ]);
        }

        // Check for existing pending registrations
        $existingPending = PendingRegistration::where('email', $email)
            ->orWhere('index_number', $data['index_number'])
            ->pending()
            ->first();

        if ($existingPending) {
            return redirect()->route('auth.register')
                ->withErrors([
                    'email' => __('A registration request with this email or index number is already pending review. Please wait for admin approval.'),
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Determine registration flow based on email
        if ($this->emailValidator->canAutoVerify($email, $class)) {
            // Standard flow: School email with matching prefix
            return $this->handleStandardRegistration($request, $data, $fullName);
        } else {
            // Manual verification flow: Non-school email or mismatched prefix
            return $this->handlePendingRegistration($data, $fullName);
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
                    'email' => __('An account for this index number is already pending with a different email. Please use the original email address or contact the ACSES team for assistance.'),
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
     */
    private function handlePendingRegistration(array $data, string $fullName): RedirectResponse
    {
        $this->pendingService->create([
            'fullname' => $fullName,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'index_number' => $data['index_number'],
            'class' => $data['class'],
            'year' => $data['year'],
        ]);

        return redirect()->route('login')
            ->with('status', __('Your registration request has been submitted for review. You will receive an email once an administrator has reviewed your application.'))
            ->with('pending_registration', true);
    }
}
