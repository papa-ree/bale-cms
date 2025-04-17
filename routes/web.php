<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;
use Paparee\BaleCms\App\Livewire\Landlord\Dashboard\Index;
use Paparee\BaleCms\App\Livewire\SharedComponents\Pages\UserProfile\Index as UserProfileIndex;
use Paparee\BaleCms\App\Livewire\Tenant\Dashboard\Index as TenantDashboardIndex;
use Paparee\BaleCms\App\Livewire\TwoFactorAuthenticatedSessionController;
use Paparee\BaleCms\App\Livewire\UpdateFirebaseToken;

Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'id'])) {
        abort(400);
    }

    session()->put('locale', $locale);

    return redirect()->back();
});

// landing with theme route
Route::localizedGroup(function () {
    Route::middleware([
        'set locale',
    ])->group(function () {
        Route::post('entrance.gate', [AuthenticatedSessionController::class, 'store'])
            ->middleware(['recaptcha', 'throttle:login'])
            ->name('login');

        Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
            $twoFactorLimiter = config('fortify.limiters.two-factor');

            Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
                ->middleware(array_filter([
                    'guest:'.config('fortify.guard'),
                    $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
                ]));
        });

        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {

            Route::patch('update-fcm-token', [UpdateFirebaseToken::class, 'updateToken'])->name('update-fcm-token');

            // redirect route
            Route::get('/dashboard', function () {
                $user = Auth::user();
            
                if ($user->tenant) {
                    return redirect()->route('tenant-dashboard.index');
                }
            
                return redirect()->route('landlord-dashboard.index');
            })->name('dashboard');

            // dashboard route
            Route::group(['middleware' => ['role:developer|admin', 'permission:dashboard']], function () {
                Route::name('landlord-dashboard.')->group(function () {
                    Route::get('dashboard/landlord', Index::class)->name('index');
                });
            });

            // tenant dashboard route
            Route::group(['middleware' => ['role:developer|tenant', 'permission:dashboard']], function () {
                Route::name('tenant-dashboard.')->group(function () {
                    Route::get('dashboard/tenant', TenantDashboardIndex::class)->name('index');
                });
            });

            Route::group(['middleware' => ['permission:manage user profile']], function () {
                Route::name('user-profile.')->group(function () {
                    Route::get('user.profiles', UserProfileIndex::class)->name('index');
                });
            });

        //     // redirect route
        //     Route::get('user/profile', function () {
        //         return redirect()->route('user-profile.index');
        //     })->name('profile.show');

        });
    });
});
