<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DocumentApproveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', trans('app.unauthorized_access'));
        }

        $user = Auth::user();

        if ($user->broker_detail) {
            if ($user->broker_detail->approved == 0) {
                Auth::logout();
                return redirect()->guest(route('login'))->with('error', 'Owner is verifying your document');
            }
        }
        return $next($request);
    }
}
