<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth('admin')->user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        if (!$user->can($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
