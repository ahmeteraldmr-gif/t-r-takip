<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatron
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isPatron()) {
            abort(403, 'Bu sayfaya sadece patron erişebilir.');
        }

        return $next($request);
    }
}
