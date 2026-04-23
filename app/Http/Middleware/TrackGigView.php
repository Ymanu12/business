<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackGigView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->routeIs('gigs.show') && $request->isMethod('GET')) {
            $gig = $request->route('gig');
            $key = "gig_view:{$gig->id}:{$request->ip()}";

            if (! Cache::has($key)) {
                Cache::put($key, true, now()->addHours(24));
                Cache::increment("gig:{$gig->id}:views");
            }
        }

        return $response;
    }
}
