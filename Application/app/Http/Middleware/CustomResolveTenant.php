<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Slides\Saml2\Http\Middleware\ResolveTenant;

class CustomResolveTenant extends ResolveTenant
{
  /**
   * Resolve a tenant by a request.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Slides\Saml2\Models\Tenant|null
   */
  protected function resolveTenant($request)
  {
      if(!$uuid = $request->route('uuid')) {
          if (config('saml2.debug')) {
              Log::debug('[Saml2] Tenant UUID is not present in the URL so cannot be resolved', [
                  'url' => $request->fullUrl()
              ]);
          }

          return null;
      }

      if (is_numeric($uuid)) {
        $tenant = $this->tenants->findById($uuid);
      } else {
        $tenant = $this->tenants->findByAnyIdentifier($uuid)->first();
      }

      if(!$tenant) {
          if (config('saml2.debug')) {
              Log::debug('[Saml2] Tenant doesn\'t exist', [
                  'uuid' => $uuid
              ]);
          }

          return null;
      }

      if($tenant->trashed()) {
          if (config('saml2.debug')) {
              Log::debug('[Saml2] Tenant #' . $tenant->id. ' resolved but marked as deleted', [
                  'id' => $tenant->id,
                  'uuid' => $uuid,
                  'deleted_at' => $tenant->deleted_at->toDateTimeString()
              ]);
          }

          return null;
      }

      return $tenant;
  }

}
