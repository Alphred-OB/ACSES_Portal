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
        $student = $request->user('student');

        if ($student) {
            $hasOutstanding = Due::query()
                ->where('student_id', $student->getAuthIdentifier())
                ->whereIn('payment_status', ['owing', 'pending_verification'])
                ->where('is_active', true)
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
