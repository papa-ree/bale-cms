<?php

namespace Paparee\BaleCms\App\Livewire;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController as DefaultTwoFactorAuthenticatedSessionController;

class TwoFactorAuthenticatedSessionController extends DefaultTwoFactorAuthenticatedSessionController
{
    // /**
    //  * The guard implementation.
    //  *
    //  * @var \Illuminate\Contracts\Auth\StatefulGuard
    //  */
    // protected $guard;

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
    //  * @return void
    //  */
    // public function __construct(StatefulGuard $guard)
    // {
    //     $this->guard = $guard;
    // }

    // /**
    //  * Show the two factor authentication challenge view.
    //  *
    //  * @param  \Laravel\Fortify\Http\Requests\TwoFactorLoginRequest  $request
    //  * @return \Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse
    //  */
    // public function create(TwoFactorLoginRequest $request): TwoFactorChallengeViewResponse
    // {
    //     if (! $request->hasChallengedUser()) {
    //         throw new HttpResponseException(redirect()->route('login'));
    //     }

    //     return app(TwoFactorChallengeViewResponse::class);
    // }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @param  \Laravel\Fortify\Http\Requests\TwoFactorLoginRequest  $request
     * @return mixed
     */
    public function store(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $request->hasValidCode()) {
            event(new TwoFactorAuthenticationFailed($user));

            return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        $this->storeCookieIfNotInDB($user);

        return app(TwoFactorLoginResponse::class);
    }

    /**
     * Store the cookie if it is not in the database.
     *
     * @param  \App\Models\User\User  $user
     * @return void
     */
    protected function storeCookieIfNotInDB($user)
    {
        $two_factor_cookies = json_decode($user->two_factor_cookies);
        if (!is_array($two_factor_cookies)){
            $two_factor_cookies = [];
        }
        $two_factor_cookie = Cookie::get('2fa');

        if (!in_array($two_factor_cookie, $two_factor_cookies)) {
            $two_factor_cookie = md5(now());
            $two_factor_cookies[] = $two_factor_cookie;
            if (count($two_factor_cookies) > 3) {
                array_shift($two_factor_cookies);
            }

            $user->two_factor_cookies = json_encode($two_factor_cookies);
            $user->save();

            $lifetime = 60 * 24 * 365; //one year
            Cookie::queue('2fa',$two_factor_cookie,$lifetime);
        }
    }
}
