<?php

use App\Livewire\Landlord\Dashboard\Index;
use App\Livewire\Tenant\Dashboard\Index as TenantDashboardIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;
use Livewire\Volt\Volt;
use Paparee\BaleCms\App\Controller\TwoFactorAuthenticatedSessionController;
use Paparee\BaleCms\App\Controller\UpdateFirebaseTokenController;
use Paparee\BaleNawasara\App\Controllers\DnsRecordController;

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
            });

            Route::group(['middleware' => ['permission:token read']], function () {
                Route::name('tokens.')->group(function () {
                    Volt::route('tokens', 'nawasara/pages/token/index')->name('index');
                    Volt::route('tokens.create', 'nawasara/pages/token/token-cru')->name('create');
                });
            });

            Route::group(['middleware' => ['permission:contact read']], function () {
                Route::name('contacts.')->group(function () {
                    Volt::route('contacts', 'nawasara/pages/contact/index')->name('index');
                    Volt::route('contacts.create.{contact}', 'nawasara/pages/contact/contact-cru')->name('create');
                    Volt::route('contacts.edit.{contact}', 'nawasara/pages/contact/contact-cru')->name('edit');
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
        });
    });
});
