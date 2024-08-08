<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\SamlUserLoggedIn;
use App\Listeners\SamlUserLoggedOut;
use Slides\Saml2\Events\SignedIn;
use Slides\Saml2\Events\SignedOut;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SignedIn::class => [
            SamlUserLoggedIn::class,
        ],
        SignedOut::class => [
            SamlUserLoggedOut::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
