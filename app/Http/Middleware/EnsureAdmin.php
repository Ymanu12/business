<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Accès réservé aux administrateurs.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Cette page est réservée aux administrateurs.');
        }

        return $next($request);
    }
}
