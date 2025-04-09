<?php

use App\Http\Controllers\ImagePreviewController;
use App\Http\Controllers\TwoFactorAuthenticatedSessionController;
use App\Http\Controllers\UpdateFirebaseToken;
use App\Livewire\Pages\Account\AccountCru;
use App\Livewire\Pages\Account\Index as AccountIndex;
use App\Livewire\Pages\ActivityLog\DetailActivityLog;
use App\Livewire\Pages\ActivityLog\Index as ActivityLogIndex;
use App\Livewire\Pages\Announcement\AnnouncementCru;
use App\Livewire\Pages\Announcement\Index as AnnouncementIndex;
use App\Livewire\Pages\Category\CategoryCru;
use App\Livewire\Pages\Category\Index as CategoryIndex;
use App\Livewire\Pages\Contact\Index as ContactIndex;
use App\Livewire\Pages\Dashboard\Index;
use App\Livewire\Pages\Hero\Index as HeroIndex;
use App\Livewire\Pages\LoginLog\Index as LoginLogIndex;
use App\Livewire\Pages\Navigation\CreateNewNavigation;
use App\Livewire\Pages\Navigation\EditNavigation;
use App\Livewire\Pages\Navigation\Index as NavigationIndex;
use App\Livewire\Pages\PageManagement\CreateNewPage;
use App\Livewire\Pages\PageManagement\EditPage;
use App\Livewire\Pages\PageManagement\Index as PageManagementIndex;
use App\Livewire\Pages\Permission\Index as PermissionIndex;
use App\Livewire\Pages\Permission\PermissionCru;
use App\Livewire\Pages\Post\CreateNewPost;
use App\Livewire\Pages\Post\Index as PostIndex;
use App\Livewire\Pages\Post\PostEditor;
use App\Livewire\Pages\Role\Index as RoleIndex;
use App\Livewire\Pages\Role\Section\RoleCru;
use App\Livewire\Pages\Service\EditService;
use App\Livewire\Pages\Service\Index as ServiceIndex;
use App\Livewire\Pages\Site\Index as SiteIndex;
use App\Livewire\Pages\ThemeManagement\Index as ThemeManagementIndex;
use App\Livewire\Pages\UserProfile\Index as UserProfileIndex;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Livewire\Volt\Volt;
use Paparee\BaleCms\App\Models\ThemeManagement;

Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'id'])) {
        abort(400);
    }

    session()->put('locale', $locale);

    return redirect()->back();
});

// media url
// Route::get('image/{filename}', [ImagePreviewController::class, 'preview'])->name('image.preview');
// Route::get('image/media/{filename}', [ImagePreviewController::class, 'media'])->name('image.media');
// Route::get('icon/{filename}', [ImagePreviewController::class, 'icon'])->name('icon.preview');

// landing with theme route
Route::localizedGroup(function () {
    Route::middleware([
        'set locale',
    ])->group(function () {
        if (Schema::hasTable('theme_management')) {
            $actived_theme = ThemeManagement::whereActived(true)->first();
            if ($actived_theme) {
                Volt::route('/', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/index')->name('welcome');

                // show services
                Volt::route('/services', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/service/index')->name('landing.service');

                // show contacts
                Volt::route('/contacts', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/contact/index')->name('landing.contact');

                // show post
                Volt::route('/post-list', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/post/index')->name('landing.post');
                Volt::route('/post-list/{post}', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/post/view-post')->name('landing.view-post');

                // show page
                Volt::route('page/{page}', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/page/index')->name('landing.view-page');

                // show category
                Volt::route('get-category/{category}', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/category/index')->name('landing.get-category');

                // show tag
                Volt::route('get-tag/{tag}', 'themes/'.$actived_theme->theme_vendor.'/'.$actived_theme->theme_name.'/resources/views/pages/tag/index')->name('landing.get-tag');
            }
        }

        Route::post('entrance.gate', [AuthenticatedSessionController::class, 'store'])
            ->middleware(['recaptcha', 'throttle:login'])
            ->name('login');

        // Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
        //     $twoFactorLimiter = config('fortify.limiters.two-factor');

        //     Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
        //         ->middleware(array_filter([
        //             'guest:'.config('fortify.guard'),
        //             $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
        //         ]));
        // });

        // Route::middleware([
        //     'auth:sanctum',
        //     config('jetstream.auth_session'),
        //     'verified',
        // ])->group(function () {

        //     Route::group(['middleware' => ['permission:dashboard']], function () {
        //         Route::name('dashboard.')->group(function () {
        //             Route::get('dashboard', Index::class)->name('index');
        //         });
        //     });

        //     Route::patch('update-fcm-token', [UpdateFirebaseToken::class, 'updateToken'])->name('update-fcm-token');

        //     Route::group(['middleware' => ['permission:manage post']], function () {
        //         Route::name('post.')->group(function () {
        //             Route::get('post', PostIndex::class)->name('index');
        //             Route::get('post.create', CreateNewPost::class)->name('create');
        //             Route::get('post.edit.{post}', PostEditor::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage site']], function () {
        //         Route::name('site.')->group(function () {
        //             Route::get('site', SiteIndex::class)->name('index');
        //         });
        //     });
        //     Route::group(['middleware' => ['permission:manage contact']], function () {
        //         Route::name('contact.')->group(function () {
        //             Route::get('contact', ContactIndex::class)->name('index');
        //         });
        //     });
            

        //     // Route::group(['middleware' => ['permission:manage gallery']], function () {
        //     //     Route::name('gallery.')->group(function () {
        //     //         Route::get('gallery', GalleryIndex::class)->name('index');
        //     //     });
        //     // });

        //     // account management
        //     Route::group(['middleware' => ['permission:manage account']], function () {
        //         Route::name('accounts.')->group(function () {
        //             Route::get('accounts', AccountIndex::class)->name('index');
        //             Route::get('accounts.create.{user}', AccountCru::class)->name('create');
        //             Route::get('accounts.edit.{user}', AccountCru::class)->name('edit');
        //         });
        //     });

        //     // theme management
        //     Route::group(['middleware' => ['permission:manage theme']], function () {
        //         Route::name('themes.')->group(function () {
        //             Route::get('themes', ThemeManagementIndex::class)->name('index');
        //         });
        //     });

        //     // role management
        //     Route::group(['middleware' => ['permission:role management']], function () {
        //         Route::name('roles.')->group(function () {
        //             Route::get('roles', RoleIndex::class)->name('index');
        //             Route::get('roles.create', RoleCru::class)->name('create');
        //             Route::get('roles.edit.{role}', RoleCru::class)->name('edit');
        //         });
        //     });

        //     // role management
        //     Route::group(['middleware' => ['permission:permission management']], function () {
        //         Route::name('permissions.')->group(function () {
        //             Route::get('permissions', PermissionIndex::class)->name('index');
        //             Route::get('permissions.create.{permission}', PermissionCru::class)->name('create');
        //             Route::get('permissions.edit.{permission}', PermissionCru::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage announcement']], function () {
        //         Route::name('announcement.')->group(function () {
        //             Route::get('announcement', AnnouncementIndex::class)->name('index');
        //             Route::get('announcement.create', AnnouncementCru::class)->name('create');
        //             Route::get('announcement.edit.{announcement}', AnnouncementCru::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage category']], function () {
        //         Route::name('category.')->group(function () {
        //             Route::get('category', CategoryIndex::class)->name('index');
        //             Route::get('category.create.{category}', CategoryCru::class)->name('create');
        //             Route::get('category.edit.{category}', CategoryCru::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage service']], function () {
        //         Route::name('service.')->group(function () {
        //             Route::get('service', ServiceIndex::class)->name('index');
        //             Route::get('service.edit.{service}', EditService::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage hero']], function () {
        //         Route::name('hero.')->group(function () {
        //             Route::get('hero', HeroIndex::class)->name('index');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:manage page']], function () {
        //         Route::name('page-management.')->group(function () {
        //             Route::get('page-management', PageManagementIndex::class)->name('index');
        //             Route::get('page-management.create', CreateNewPage::class)->name('create');
        //             Route::get('page-management.edit.{page}', EditPage::class)->name('edit');
        //         });
        //     });
        //     // });

        //     Route::group(['middleware' => ['permission:manage navigation']], function () {
        //         Route::name('navigation.')->group(function () {
        //             Route::get('navigation', NavigationIndex::class)->name('index');
        //             Route::get('navigation.create', CreateNewNavigation::class)->name('create');
        //             Route::get('navigation.edit.{navigation}', EditNavigation::class)->name('edit');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:view activity log']], function () {
        //         Route::name('activity-logs.')->group(function () {
        //             Route::get('activity-logs', ActivityLogIndex::class)->name('index');
        //             Route::get('activity-logs.detail.{activityLog}', DetailActivityLog::class)->name('detail');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:view login log']], function () {
        //         Route::name('login-logs.')->group(function () {
        //             Route::get('login-logs', LoginLogIndex::class)->name('index');
        //             // Route::get('login-logs.detail.{loginLog}', DetailActivityLog::class)->name('detail');
        //         });
        //     });

        //     Route::group(['middleware' => ['permission:api management']], function () {
        //         Route::get('user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        //     });

        //     Route::group(['middleware' => ['permission:manage user profile']], function () {
        //         Route::name('user-profile.')->group(function () {
        //             Route::get('user.profiles', UserProfileIndex::class)->name('index');
        //         });
        //     });

        //     // redirect route
        //     Route::get('user/profile', function () {
        //         return redirect()->route('user-profile.index');
        //     })->name('profile.show');

        // });
    });
});
