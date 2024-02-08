<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }

        // Use boostrap pagination
        //Paginator::useBootstrap();
        // Shere pages data with pages layout
        View::composer(['layouts.pages'], 'App\Http\View\Composers\PagesComposer');
        // Share messages data with admin layout
        View::composer(['layouts.admin'], 'App\Http\View\Composers\MessagesComposer');
        // Shere website data
        View::composer([
            'layouts.pages',
            'layouts.admin',
            'layouts.user',
            'layouts.approval',
            'layouts.public',
            'layouts.landing',
            'layouts.no-category',
            'includes.head',
            'includes.footer',
            'includes.admin.head',
            'pages.home',
            'pages.landing',
            'pages.upload',
            'pages.index',
            'pages.views',
        ],
            'App\Http\View\Composers\WebsiteDataComposer'
        );
    }
}
