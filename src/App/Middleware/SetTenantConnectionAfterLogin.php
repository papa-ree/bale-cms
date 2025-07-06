<?php

namespace Paparee\BaleCms\App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Paparee\BaleNawasara\App\Jobs\GenerateNawasaraSummaryJob;
use Symfony\Component\HttpFoundation\Response;

class SetTenantConnectionAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->tenant) {
            $tenantDb = $user->tenant->database;

            // Ambil base config dari bale-cms
            $baseTenantConfig = config('bale-cms.database.tenant');

            // Set konfigurasi koneksi tenant secara dinamis
            Config::set('database.connections.tenant', array_merge(
                $baseTenantConfig,
                ['database' => $tenantDb]
            ));

            DB::purge('tenant');
            DB::reconnect('tenant');

            session([
                'user_type' => 'tenant',
                'tenant_db' => $tenantDb
            ]);
        } else {
            GenerateNawasaraSummaryJob::dispatch();

            session(['user_type' => 'landlord']);
        }

        return $next($request);
    }
}
