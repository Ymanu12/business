<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if ($user?->role === UserRole::Freelancer && ! $user->freelancerProfile()->exists()) {
            return redirect()->route('freelancer.onboarding');
        }

        return redirect()->intended(route('dashboard'));
    }
}
