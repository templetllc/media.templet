<?php

namespace App\Listeners;

use App\SamlSession;
use Slides\Saml2\Events\SignedOut;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SamlUserLoggedOut
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Slides\Saml2\Events\SignedOut  $event
     * @return void
     */
    public function handle(SignedOut $event)
    {
        $app = app();
        $samlSession = new SamlSession($app);
        if ($samlSession->exists()) {
            $samlSession->clear();
        }
        Auth::logout();
        Session::save();
    }

}
