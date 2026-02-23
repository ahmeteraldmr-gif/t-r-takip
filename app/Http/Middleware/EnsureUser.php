<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

        $user = User::firstOrCreate(
            ['email' => 'kullanici@tirtek.com'],
            ['name' => 'Kullanıcı', 'password' => Hash::make('sifre123')]
        );

        Auth::login($user);

        return $next($request);
    }
}
