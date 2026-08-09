<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController as JetstreamUserProfileController;
use Laravel\Jetstream\Jetstream;
use Laravel\Passkeys\Passkey;

/**
 * Jetstream's profile controller only shares sessions and two factor state, so
 * we extend it to add the passkey props the profile page needs. The container
 * binding in AppServiceProvider swaps this in for Jetstream's controller.
 */
class UserProfileController extends JetstreamUserProfileController
{
    /**
     * Show the general profile settings screen.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        $this->validateTwoFactorAuthenticationState($request);

        return Jetstream::inertia()->render($request, 'Profile/Show', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            'sessions' => $this->sessions($request)->all(),
            'canManagePasskeys' => Features::canManagePasskeys(),
            'passkeys' => $this->passkeys($request),
        ]);
    }

    /**
     * Get the current user's passkeys, shaped for display.
     *
     * @return list<array<string, mixed>>
     */
    protected function passkeys(Request $request): array
    {
        if (! Features::canManagePasskeys()) {
            return [];
        }

        return $request->user()
            ->passkeys()
            ->latest()
            ->get()
            ->map(fn (Passkey $passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at_diff' => $passkey->created_at?->diffForHumans(),
                'last_used_at_diff' => $passkey->last_used_at?->diffForHumans(),
            ])
            ->values()
            ->all();
    }
}
