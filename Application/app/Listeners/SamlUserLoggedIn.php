<?php

namespace App\Listeners;

use App\SamlSession;
use App\Models\User;
use App\Models\Category;
use Slides\Saml2\Events\SignedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class SamlUserLoggedIn
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
     * @param  \Slides\Saml2\Events\SignedIn  $event
     * @return void
     */
    public function handle(SignedIn $event)
    {
        $samlUser = $event->auth->getSaml2User();

        $tenantMetadata = $event->auth->getTenant()->metadata;
        $jit = false;
        if(isset($tenantMetadata["options_jit"])) {
            $jit = $tenantMetadata["options_jit"];
        }
        $sync_user = false;
        if(isset($tenantMetadata["options_sync_user"])) {
            $sync_user = $tenantMetadata["options_sync_user"];
        }

        $userMapping = [];
        foreach (array_keys($tenantMetadata) as $key) {
            if (str_starts_with($key, "mapping_")) {
                $real_key = str_replace("mapping_", "", $key);
                $userMapping[$real_key] = $tenantMetadata[$key];
            }
        }

        $samlUser->parseAttributes($userMapping); // User Attributes converted based on metadata and injected in samlUser
        $email = "";
        $name = "";
        $permission = null;
        $category = null;

        if (empty($samlUser->email)) {
            $errorMsg = 'Email attribute was not provided by the IdP';
            Log::error('[Saml2] '.$errorMsg);
            session()->flash('error', $errorMsg);
            abort(redirect('login'));
        }

        $email = $samlUser->email[0];

        if (!empty($samlUser->name)) {
            $name = $samlUser->name[0];
        }
        if (!empty($samlUser->permission)) {
            $data = $samlUser->permission[0];
            if (!in_array($data, [1,2,3,4,5])) {
                $errorMsg = "Provided permisision ".$data." doesn't exist";
                Log::debug('[Saml2] '.$errorMsg);
            } else {
                $permission = $data;
            }
        }
        if (!empty($samlUser->category)) {
            $data = $samlUser->category[0];
            $categoryObj = Category::where('category', $data)->first();
            if (!isset($categoryObj)) {
              $errorMsg = "Provided category ".$data." doesn't exist";
              Log::debug('[Saml2] '.$errorMsg);
            } else {
                $category = $categoryObj->id;
            }
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            if (!$jit) {
                $errorMsg = 'User does not exists and Just-in-time provisioning is disabled';
                Log::error('[Saml2] '.$errorMsg);
                session()->flash('error', $errorMsg);
                abort(redirect('login'));
            }

            $avatar = "default.png";
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'avatar' => $avatar,
                'password' => bcrypt(Str::random(14))
            ]);
            if ($permission) {
                $user->update([
                    'permission' => $permission,
                ]);
            }
            if ($category) {
                $user->update([
                    'category' => $category,
                ]);
            }
        } else if ($sync_user) {
            $user->update([
                'name' => $name,
            ]);
            if ($permission) {
                $user->update([
                    'permission' => $permission,
                ]);
            }
            if ($category) {
                $user->update([
                    'category' => $category,
                ]);
            }
        }

        Auth::login($user);
        $samlSession = new SamlSession();
        $samlSession->store($samlUser->getTenant(), $samlUser);
    }
}
