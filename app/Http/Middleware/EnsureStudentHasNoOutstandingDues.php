<?php

namespace App\Http\Middleware;

use App\Models\Due;
use Closure;
use Illuminate\Http\Request;

class EnsureStudentHasNoOutstandingDues
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Allow access to Profile, Dues, and Logout for all students
        $allowedRoutes = [
            'student.profile',
            'student.profile.update',
            'student.profile.devices.revoke',
            'student.profile.devices.revoke-all',
            'student.dues.index',
            'student.payments.paystack.initialize',
            'student.payments.paystack.callback',
            'student.payments.paystack.receipt',
            'student.payments.rushpay.initialize',
            'student.payments.rushpay.checkout',
            'student.payments.rushpay.callback',
            'student.payments.manual.submit',
            'auth.logout'
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        $student = $request->user('student');

        if ($student) {
            // Check for actual outstanding dues
            // We ignore 'pending_verification' IF it's an automated payment (paystack/rushpay), 
            // but for manual payments (admin intercept) they stay blocked.
            $hasOutstanding = Due::query()
                ->where('student_id', $student->getAuthIdentifier())
                ->where('is_active', true)
                ->where(function($q) {
                    $q->where('payment_status', 'owing')
                      ->orWhere(function($sq) {
                          $sq->where('payment_status', 'pending_verification')
                             ->whereNotIn('payment_method', ['paystack', 'rushpay']);
                      });
                })
                ->exists();

            if ($hasOutstanding) {
                return redirect()
                    ->route('student.dues.index')
                    ->with('status', __('You currently have outstanding dues. Please settle your dues before accessing this section.'));
            }
        }

        return $next($request);
    }
}
