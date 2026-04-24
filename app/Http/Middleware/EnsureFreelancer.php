<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFreelancer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isFreelancer()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Accès réservé aux freelances.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Cette page est réservée aux freelances.');
        }

        return $next($request);
    }
}
