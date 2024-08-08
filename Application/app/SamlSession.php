<?php

namespace App;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Slides\Saml2\Models\Tenant;
use Slides\Saml2\Facades\Auth;
use Slides\Saml2\Repositories\TenantRepository;
use Slides\Saml2\OneLoginBuilder;
use Slides\Saml2\Saml2User;
use OneLogin\Saml2\Error as OneLoginError;

/**
 * Class Session
 */
class SamlSession
{
    /**
     * @var OneLoginBuilder
     */
    protected $builder;

    public function __construct()
    {
        $app = app();
        $this->builder = new OneLoginBuilder($app);
    }

    public function exists(): bool
    {
        return Cookie::has('saml_tenant_id');
    }

    public function store(Tenant $tenant, Saml2User $samlUser): void
    {
        Cookie::queue(cookie()->make('saml_tenant_id', $tenant->id, config('session.lifetime')));
        Cookie::queue(cookie()->make('saml_session_id', $samlUser->getSessionIndex(), config('session.lifetime')));
        Cookie::queue(cookie()->make('saml_name_id', $samlUser->getNameId(), config('session.lifetime')));
    }

    public function clear(): void
    {
        Cookie::queue(cookie()->forget('saml_tenant_id'));
        Cookie::queue(cookie()->forget('saml_session_id'));
        Cookie::queue(cookie()->forget('saml_name_id'));
    }

    /**
     * Generates the redirect url to initiate a SSO
     * sign out for a user with the IdP.
     * \Illuminate\Http\Request $request
     * \Slides\Saml2\Models\Tenant $tenant
     */
    public function login($request, $tenant): ?RedirectResponse
    {
        $this->builder
            ->withTenant($tenant)
            ->bootstrap();

        try {
            $redirectUrl = $tenant->relay_state_url ?: config('saml2.loginRoute');
            $redirectUrl = $request->query('returnTo', $redirectUrl);
            $ssoUrl = Auth::login($redirectUrl, [], false, false, true);
        } catch (OneLoginError $e) {
            report($e);
            return null;
        }

        return redirect($ssoUrl)->withHeaders([
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }

    /**
     * Generates the redirect url to initiate a global session
     * sign out for a user with the IdP.
     * \Illuminate\Http\Request $request
     * \Slides\Saml2\Models\Tenant $tenant
     */
    public function logout($tenant): ?RedirectResponse
    {
        if (!$this->exists()) {
            return null;
        }

        $this->builder
            ->withTenant($tenant)
            ->bootstrap();

        try {
            $sloUrl = Auth::logout(
                config('saml2.logoutRoute'),
                Cookie::get('saml_name_id'),
                Cookie::get('saml_session_id'),
                null,
                true
            );
        } catch (OneLoginError $e) {
            report($e);
            return null;
        }

        return redirect($sloUrl)->withHeaders([
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }

    public function getTenant($tenant_id)
    {
        $class = config('saml2.tenantModel', Tenant::class);
        $query = $class::query();
        $tenant = $query->where('id', $tenant_id)->get()->first();
        return $tenant;
    }
}
