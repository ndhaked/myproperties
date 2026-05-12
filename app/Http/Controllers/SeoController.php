<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function laravelExpertJaipur()
    {
        return view('seo.laravel-expert-jaipur');
    }

    public function hireLaravelDeveloperJaipur()
    {
        return view('seo.hire-laravel-developer-jaipur');
    }

    public function seniorLaravelDeveloperIndia()
    {
        return view('seo.senior-laravel-developer-india');
    }

    public function laravelApiDevelopment()
    {
        return view('seo.laravel-api-development');
    }

    /**
     * Generate sitemap.xml with all public URLs
     */
    public function sitemap()
    {
        $pages = [
            [
                'url'        => url('/'),
                'lastmod'    => '2026-02-22',
                'changefreq' => 'weekly',
                'priority'   => '1.0',
            ],
            [
                'url'        => url('/laravel-expert-in-jaipur'),
                'lastmod'    => '2026-02-22',
                'changefreq' => 'monthly',
                'priority'   => '0.9',
            ],
            [
                'url'        => url('/hire-laravel-developer-in-jaipur'),
                'lastmod'    => '2026-02-22',
                'changefreq' => 'monthly',
                'priority'   => '0.9',
            ],
            [
                'url'        => url('/senior-laravel-developer-india'),
                'lastmod'    => '2026-02-22',
                'changefreq' => 'monthly',
                'priority'   => '0.9',
            ],
            [
                'url'        => url('/laravel-api-development'),
                'lastmod'    => '2026-02-22',
                'changefreq' => 'monthly',
                'priority'   => '0.9',
            ],
        ];

        return response()
            ->view('seo.sitemap', compact('pages'))
            ->header('Content-Type', 'text/xml');
    }
}
