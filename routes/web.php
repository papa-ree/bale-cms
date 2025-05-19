<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;
use Livewire\Volt\Volt;
use App\Livewire\Landlord\Dashboard\Index;
use App\Livewire\SharedComponents\Pages\UserProfile\Index as UserProfileIndex;
use App\Livewire\Tenant\Dashboard\Index as TenantDashboardIndex;
use Paparee\BaleCms\App\Controller\TwoFactorAuthenticatedSessionController;
use Paparee\BaleCms\App\Controller\UpdateFirebaseTokenController;

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

            Route::patch('update-fcm-token', [UpdateFirebaseTokenController::class, 'updateToken'])->name('update-fcm-token');

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

            Route::group(['middleware' => ['permission:role management']], function () {
                Route::name('roles.')->group(function () {
                    Volt::route('roles', 'shared-components/pages/role/index')->name('index');
                    Volt::route('roles.create', 'shared-components/pages/role/role-cru')->name('create');
                    Volt::route('roles.edit.{role}', 'shared-components/pages/role/role-cru')->name('edit');
                });
            });

            Route::group(['middleware' => ['permission:permission management']], function () {
                Route::name('permissions.')->group(function () {
                    Volt::route('permissions', 'shared-components/pages/permission/index')->name('index');
                });
            });

            // Route::group(['middleware' => ['permission:user management']], function () {
            //     Route::name('user-lists.')->group(function () {
            //         Volt::route('user-lists', 'shared-components/pages/user-list/index')->name('index');
            //         Volt::route('user-lists.create.{user}', 'shared-components/pages/user-list/user-cru')->name('create');
            //         Volt::route('user-lists.edit.{user}', 'shared-components/pages/user-list/user-cru')->name('edit');
            //         Volt::route('user-lists.deleted', 'shared-components/pages/user-list/deleted-user')->name('deleted');
            //     });
            // });

        //     // redirect route
        //     Route::get('user/profile', function () {
        //         return redirect()->route('user-profile.index');
        //     })->name('profile.show');

        });
    });
});
