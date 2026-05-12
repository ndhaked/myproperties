<?php

namespace App\Providers;

use App\Events\GalleryInvitationCreated;
use App\Events\RegisterInterestSubmitted;
use App\Events\RejectArtistNotification;
use App\Listeners\SendGalleryInvitationEmail;
use App\Listeners\SendRegisterInterestNotification;
use App\Listeners\SendRejectArtistEmailNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter as LaravelFilesystemAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;

use App\Events\ArtworkEnquiryCreated;
use App\Listeners\SendArtworkEnquiryEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No aliases needed anymore
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Register Azure Blob Storage Driver
        Storage::extend('azure', function ($app, $config) {
            
            // Generate the connection string dynamically
            $connectionString = sprintf(
                'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;EndpointSuffix=%s',
                $config['name'],
                $config['key'],
                $config['endpoint_suffix'] ?? 'core.windows.net'
            );

            // Create the Microsoft Client
            $client = BlobRestProxy::createBlobService($connectionString);

            // Create the Adapter (This class comes from league/flysystem-azure-blob-storage)
            $adapter = new AzureBlobStorageAdapter(
                $client,
                $config['container'],
                $config['prefix'] ?? '' // Optional prefix
            );

            // Create the Flysystem instance
            $flysystem = new Filesystem($adapter, $config);

            // Return the Laravel Adapter
            return new LaravelFilesystemAdapter($flysystem, $adapter, $config);
        });

        // 2. Force HTTPS in Production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 3. Register Components
        Blade::component('layouts.dashboard', 'dashboard-layout');
        Blade::component('layouts.marketplace', 'marketplace-layout');

        // 4. Rate Limiting
        RateLimiter::for('register-interest', function (Request $request) {
            $emailKey = strtolower((string) $request->input('email'));
            $ipKey = $request->ip() ?: 'unknown';

            return [
                Limit::perMinute(5)->by($ipKey),
                Limit::perHour(20)->by($emailKey ?: $ipKey),
            ];
        });

        // 5. Event Listeners
        Event::listen(
            RegisterInterestSubmitted::class,
            SendRegisterInterestNotification::class
        );

        Event::listen(
            GalleryInvitationCreated::class,
            SendGalleryInvitationEmail::class
        );

        Event::listen(
            RejectArtistNotification::class,
            SendRejectArtistEmailNotification::class
        );

        // Event::listen(
        //     ArtworkEnquiryCreated::class,
        //     SendArtworkEnquiryEmail::class,
        // );
    }
}