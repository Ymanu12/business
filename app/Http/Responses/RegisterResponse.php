<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if ($user?->role === UserRole::Freelancer) {
            return redirect()->route('freelancer.onboarding');
        }

        return redirect()->intended(route('dashboard'));
    }
}
