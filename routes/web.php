<?php

use App\Livewire\Landlord\Dashboard\Index;
use App\Livewire\Tenant\Dashboard\Index as TenantDashboardIndex;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;
use Livewire\Volt\Volt;
use Paparee\BaleCms\App\Controller\TwoFactorAuthenticatedSessionController;
use Paparee\BaleCms\App\Controller\UpdateFirebaseTokenController;
use Paparee\BaleNawasara\App\Controllers\DnsRecordController;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

// use Paparee\BaleNawasara\Livewire\Pages\Guest\Index as GuestIndex;

// use Socialite;

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'id'])) {
        abort(400);
    }

    session()->put('locale', $locale);

    return redirect()->back();
});

Route::get('/', function () {
    return view('welcome');
});

// Login Route ============================================================================
Route::post('/logout', function () {
    $token = session()->get('keycloak_id_token');

    // Logout of your app.
    Auth::logout();
    Session::flush(); // Clear the session data
    Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks

    // The URL the user is redirected to after logout.
    $redirectUri = Config::get('app.url');
    $url = Socialite::driver('keycloak')->getLogoutUrl();

    $params = [
        'id_token_hint' => $token, // Ambil id_token dari session
        'post_logout_redirect_uri' => $redirectUri, // URL redirect setelah logout
    ];

    $url .= '?' . http_build_query($params);

    return redirect($url);
})->name('logout');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }

    // Jika belum login di Laravel, redirect ke login.silent (biar Keycloak yang tentukan)
    return redirect()->route('login.silent');
});

Route::get('/login/silent', function () {
    return Socialite::driver('keycloak')->redirect();
})->name('login.silent');

Route::get('/force-login', function () {
    return Socialite::driver('keycloak')
        ->with(['prompt' => 'login'])
        ->redirect();
})->name('force.login');

Route::get('/login/keycloak/callback', function () {

    try {
        $user = Socialite::driver('keycloak')->user();
        // dd($user->accessTokenResponseBody['id_token']); // Debugging: tampilkan informasi user yang didapat dari Keycloak

        // Buat login ke aplikasi Laravel, bisa pakai email / ID dari Keycloak
        $authUser = User::firstOrCreate([
            'nip' => $user->getNickname(),
        ], [
            'uuid' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getNickname(),
            'email' => $user->getEmail(),
            'password' => bcrypt(Str::random(16)), // password random
        ]);

        // jika user baru maka set role sebagai guest
        if (!$authUser->getRoleNames()->first()) {
            $authUser->syncRoles('guest');
        }

        Auth::login($authUser, true);

        session(['keycloak_id_token' => $user->accessTokenResponseBody['id_token']]);
        return redirect('/dashboard');
    } catch (\Exception $e) {
        // Silent login gagal (karena user belum login di Keycloak)
        return redirect()->route('force.login'); // misalnya redirect ke login normal
    }
});
// End Login Route =======================================================================================

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
                    'guest:' . config('fortify.guard'),
                    $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
                ]));
        });

        // landing page route
        Route::name('helpdesk.')->group(function () {
            Volt::route('bantuan', 'nawasara/landing-page/helpdesk/index')->name('index');
        });
        // end landing page route

        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
            'mark user active',
        ])->group(function () {

            Route::patch('update-fcm-token', [UpdateFirebaseTokenController::class, 'updateToken'])->name('update-fcm-token');

            // redirect route
            Route::get('/dashboard', function () {
                $user = Auth::user();

                if ($user->tenant) {
                    return redirect()->route('tenant-dashboard.index');
                }

                if ($user->getRoleNames()->first() == 'guest') {
                    return redirect()->route('guest-dashboard.index');
                }

                return redirect()->route('landlord-dashboard.index');
            })->name('dashboard');

            // dashboard route
            Route::group(['middleware' => ['permission:dashboard']], function () {
                Route::name('landlord-dashboard.')->group(function () {
                    Route::get('dashboard/landlord', Index::class)->name('index');
                });
            });

            // tenant dashboard route
            Route::group(['middleware' => ['permission:dashboard tenant']], function () {
                Route::name('tenant-dashboard.')->group(function () {
                    Route::get('dashboard/tenant', TenantDashboardIndex::class)->name('index');
                });
            });

            // guest dashboard route
            Route::group(['middleware' => ['permission:waiting room']], function () {
                Route::name('guest-dashboard.')->group(function () {
                    Volt::route('guest', 'nawasara/pages/guest/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:manage user profile']], function () {
                Route::name('user-profile.')->group(function () {
                    // Route::get('user.profiles', UserProfileIndex::class)->name('index');
                    Volt::route('user.profiles', 'shared-components/pages/role/index')->name('index');
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

            Route::group(['middleware' => ['permission:user management']], function () {
                Route::name('user-lists.')->group(function () {
                    Volt::route('user-lists', 'shared-components/pages/user-list/index')->name('index');
                    Volt::route('user-lists.create.{user}', 'shared-components/pages/user-list/user-cru')->name('create');
                    Volt::route('user-lists.edit.{user}', 'shared-components/pages/user-list/user-cru')->name('edit');
                    Volt::route('user-lists.deleted', 'shared-components/pages/user-list/deleted-user')->name('deleted');
                });
            });

            //     // redirect route
            //     Route::get('user/profile', function () {
            //         return redirect()->route('user-profile.index');
            //     })->name('profile.show');

            // Nawasara Route
            Route::group(['middleware' => ['permission:domain read']], function () {
                Route::name('dns.')->group(function () {
                    Volt::route('dns', 'nawasara/pages/dns/index')->name('index');
                });

                // sync dns record by alpine trigger
                Route::post('/dns-records/sync', [DnsRecordController::class, 'sync'])->name('dns.sync');
                Route::get('/dns-records/status', [DnsRecordController::class, 'status'])->name('dns.status');
            });

            Route::group(['middleware' => ['permission:network read']], function () {
                Volt::route('/network/ip-publics', 'nawasara/pages/ip/index');
                Volt::route('/network/ip-addresses', 'nawasara/pages/ip-address/index');
            });

            Route::group(['middleware' => ['permission:token read']], function () {
                Route::name('tokens.')->group(function () {
                    Volt::route('tokens', 'nawasara/pages/token/index')->name('index');
                    Volt::route('tokens.create', 'nawasara/pages/token/token-cru')->name('create');
                });
            });

            Route::group(['middleware' => ['permission:token read']], function () {
                Route::name('helpdesks.')->group(function () {
                    Volt::route('helpdesks', 'nawasara/pages/helpdesk/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:contact read']], function () {
                Route::name('contacts.')->group(function () {
                    Volt::route('contacts', 'nawasara/pages/contact/index')->name('index');
                    Volt::route('contacts.create.{contact}', 'nawasara/pages/contact/contact-cru')->name('create');
                    Volt::route('contacts.edit.{contact}', 'nawasara/pages/contact/contact-cru')->name('edit');
                    Volt::route('contacts.assign.{contact}', 'nawasara/pages/contact/contact-cru')->name('assign');
                });
            });

            Route::group(['middleware' => ['permission:inventory overview']], function () {
                Route::name('inventory-overviews.')->group(function () {
                    Volt::route('/inventory-overviews', 'inv/pages/overview/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:inventory master item read']], function () {
                Route::name('master-items.')->group(function () {
                    Volt::route('/master-items', 'inv/pages/master-item/index')->name('index');
                    Volt::route('/master-items.create.{item}', 'inv/pages/master-item/master-item-cru')->name('create');
                    Volt::route('/master-items.edit.{item}', 'inv/pages/master-item/master-item-cru')->name('edit');
                });
            });

            Route::group(['middleware' => ['permission:inventory read']], function () {
                Route::name('inventories.')->group(function () {
                    Volt::route('/inventories', 'inv/pages/inventory/index')->name('index');
                    Volt::route('/inventories.create.{inventory}', 'inv/pages/inventory/inventory-cru')->name('create');
                    Volt::route('/inventories.edit.{inventory}', 'inv/pages/inventory/inventory-cru')->name('edit');
                    Volt::route('/inventories.add-stock', 'inv/pages/inventory/add-stock-form')->name('add-stock');
                    Volt::route('/inventories.opname-stock', 'inv/pages/inventory/opname-stock-form')->name('opname-stock');
                });
            });

            Route::group(['middleware' => ['permission:inventory movement read']], function () {
                Route::name('inventory-movements.')->group(function () {
                    Volt::route('/inventory-movements', 'inv/pages/inventory-movement/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:inventory assignment read']], function () {
                Route::name('assignments.')->group(function () {
                    Volt::route('/it-inventories', 'inv/pages/assignment/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:inventory return create']], function () {
                Route::name('returns.')->group(function () {
                    Volt::route('/returns.{item}', 'inv/pages/return/index')->name('index');
                });
            });

            Route::group(['middleware' => ['permission:inventory device type read']], function () {
                Route::name('device-types.')->group(function () {
                    Volt::route('/device-types', 'inv/pages/device-type/index')->name('index');
                });
            });

            Route::group(['middleware' => ['role:guest']], function () {
                Route::name('notification-gateway.')->group(function () {
                    Volt::route('/notification-gateway', 'guest/pages/notification-gateway/index')->name('index');
                    Volt::route('/notification-gateway.create.{template}', 'guest/pages/notification-gateway/index')->name('index');
                });
            });
        });
    });
});
