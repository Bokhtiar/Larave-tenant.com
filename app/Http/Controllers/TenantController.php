<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function create()
    {

        $tenant = Tenant::find(30);

        // Set the tenant database configuration dynamically
        Config::set('database.connections.tenant.host', "127.0.0.1");
        Config::set('database.connections.tenant.port', "3306");
        Config::set('database.connections.tenant.database', $tenant->subdomain);
        Config::set('database.connections.tenant.username', "root");
        Config::set('database.connections.tenant.password', "");

        // Reconnect to the tenant database
        DB::purge('tenant');
        DB::reconnect('tenant');

        $tenants = DB::connection('tenant')->table('tenants')->get();
        return view('tenant', compact('tenants'));
    }

    public function store(Request $request)
    {

        $tenant = Tenant::find(30);
        Config::set('database.connections.tenant.host', "127.0.0.1");
        Config::set('database.connections.tenant.port', "3306");
        Config::set('database.connections.tenant.database', $tenant->subdomain);
        Config::set('database.connections.tenant.username', "root");
        Config::set('database.connections.tenant.password', "");

        // Reconnect to the tenant database
        DB::purge('tenant');
        DB::reconnect('tenant');

        // $tenant = new Tenant();
        $tenant = array();
        $tenant['name'] = $request->name;
        $tenant['subdomain'] = $request->subdomain;
        DB::connection('tenant')->table('tenants')->insert($tenant);
       //$tenant->DB::connection('tenant')->table('tenants')->save();
        
        return back();
    }
}
