<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PageManagementController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});
Route::get('/', function () {
     return view('auth.login');
    return view('welcome');
});

// SEO Pages
Route::get('/laravel-expert-in-jaipur', [SeoController::class, 'laravelExpertJaipur'])->name('seo.laravel-expert-jaipur');
Route::get('/hire-laravel-developer-in-jaipur', [SeoController::class, 'hireLaravelDeveloperJaipur'])->name('seo.hire-laravel-developer-jaipur');
Route::get('/senior-laravel-developer-india', [SeoController::class, 'seniorLaravelDeveloperIndia'])->name('seo.senior-laravel-developer-india');
Route::get('/laravel-api-development', [SeoController::class, 'laravelApiDevelopment'])->name('seo.laravel-api-development');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectsController::class);
    Route::resource('leads', LeadsController::class);
    Route::resource('deals', DealsController::class);
    Route::resource('users', UsersController::class);
    Route::post('/users/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle_status');

    Route::post('/leads/task/store', [LeadsController::class, 'taskStore'])->name('leads.task.store');
    Route::post('/leads/notes/store', [LeadsController::class, 'noteStore'])->name('leads.notes.store');
    Route::post('/leads/logs/store', [LeadsController::class, 'logsStore'])->name('leads.logs.store');
    Route::post('/leads/assign', [LeadsController::class, 'assignLeads'])->name('leads.assign');
    Route::post('/leads/bulk-delete', [LeadsController::class, 'bulkDestroy'])->name('leads.bulk_delete');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/account', [ProfileController::class, 'account'])->name('profile.account');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::post('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/help-support', [SettingsController::class, 'helpSupport'])->name('help-support.index');
});

require __DIR__ . '/auth.php';

Route::post('newsletter-subscription', [FrontController::class, 'newsletterSubscription'])->name('newsletter.subscription');
Route::post('/contact-submit', [ContactController::class, 'store'])->name('contactusSubmit');

//use for development purpose only
Route::get('/run-manual-migrations', function () {
    try {
        // Run migrations
        Artisan::call('migrate --force');

        return "<h3 style='color:green'>Migrations executed successfully!</h3>";
    } catch (\Exception $e) {
        return "<h3 style='color:red'>Error: " . $e->getMessage() . "</h3>";
    }
});
Route::get('/run-manual-seeders', function () {
    try {
        // Run seeders
        Artisan::call('db:seed --force');

        return "<h3 style='color:green'>Seeders executed successfully!</h3>";
    } catch (\Exception $e) {
        return "<h3 style='color:red'>Error: " . $e->getMessage() . "</h3>";
    }
});