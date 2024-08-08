<?php

namespace App\Http\Controllers\Auth;

use App\SamlSession;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\LegacyUi\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cookie;
use Slides\Saml2\Models\Tenant;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        $saml_sso_url = null;
        // For now we retrieve the first tenant, when more tenants available we
        // will need to create an intermediare view
        $tenant = Tenant::get()->first();
        if ($tenant && $tenant->idp_login_url) {
            $tenantMetadata = $tenant->metadata;
            $samlEnabled = false;
            if(isset($tenantMetadata["saml_status"]) && $tenantMetadata["saml_status"]) {
                if(isset($tenantMetadata["options_force_saml"]) && $tenantMetadata["options_force_saml"]) {
                    if ($request->query('skipsaml') !== "1") {
                        $app = app();
                        $samlSession = new SamlSession($app);
                        return $samlSession->login($request, $tenant);
                    }
                }
                $saml_sso_url = URL::route('saml.login', ['uuid' => $tenant->uuid]);
            }
        }

        return view('auth.login', ['saml_sso_url' => $saml_sso_url]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $app = app();
        $samlSession = new SamlSession($app);
        if ($samlSession->exists()) {
            $tenant_id = Cookie::get('saml_tenant_id');
            $tenant = $samlSession->getTenant($tenant_id);
            if ($tenant && !empty($tenant->idp_logout_url)) {
                return $samlSession->logout($tenant);
            }
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
