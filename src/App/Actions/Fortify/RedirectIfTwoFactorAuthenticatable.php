<?php

namespace Paparee\BaleCms\App\Actions\Fortify;

use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as DefaultRedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\TwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable extends DefaultRedirectIfTwoFactorAuthenticatable
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if (optional($user)->two_factor_secret &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user)) &&
            $this->checkIfUserDeviceHasNotCookie($user)) {
            return $this->twoFactorChallengeResponseBale($request, $user);
        }

        return $next($request);
    }

    /**
     * This checks if the user's device has the cookie stored 
     * in the database.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    protected function checkIfUserDeviceHasNotCookie($user)
    {
        $two_factor_cookies = json_decode($user->two_factor_cookies);
        if (!is_array($two_factor_cookies)){
            $two_factor_cookies = [];
        }
        $two_factor_cookie = Cookie::get('2fa');
        return !in_array($two_factor_cookie,$two_factor_cookies);
    }

    protected function twoFactorChallengeResponseBale($request, $user)
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->boolean('remember'),
        ]);

        TwoFactorAuthenticationChallenged::dispatch($user);

        return $request->wantsJson()
                    ? response()->json(['two_factor' => true])
                    : redirect()->route('two-factor.login');
    }

}
