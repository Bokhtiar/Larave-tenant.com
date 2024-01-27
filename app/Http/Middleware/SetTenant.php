<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $subdomain = explode('.', request()->getHost())[0];
       
        $tenant = Tenant::where('subdomain', $subdomain)->first();


        // Get the current database connection name
        // $currentConnection = DB::getDefaultConnection();
       


        if ($tenant) {
            $exist = Config::set('database.connections.tenant.database', $tenant->subdomain  );
            if ($exist == null) {
                //dd("got not db");
                $this->createTenantDatabase($tenant->subdomain);
                return $next($request);
            }else{
                DB::reconnect('tenant');
                return $next($request);
            }
        }else{
            return dd('is not valid domain');
        }
        return $next($request);
    }

    protected function createTenantDatabase($tenant)
    {
        $databaseName =  $tenant;
        DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");
        // Configure the connection for the newly created database
        Config::set('database.connections.tenant.database', $databaseName);
        
        // Reconnect to the new database
        DB::purge('tenant');
        DB::reconnect('tenant');

       

        $currentConnection = "tenant";

        // Get the database name for the current connection
        $currentDatabase = config("database.connections.{$currentConnection}.database");

        // Output the results
        echo "Current Connection: $currentConnection\n";
        echo "Current Database: $currentDatabase\n";


        // Run migrations for the tenant
        $this->runTenantMigrations();
    }

    protected function runTenantMigrations()
    {

        // $migrationPath = base_path('database/migrations/user');
        // Run migrations for the 'tenant' connection
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/', // Adjust the path as needed
        ]);

        // Artisan::call('migrate', [
        //     '--database' => 'tenant',
        //     '--path' => $migrationPath, // specific table insert
        // ]); 
    }
}
