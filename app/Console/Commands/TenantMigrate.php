<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shared\Tenant;

class TenantMigrate extends Command
{

    protected $signature = 'tenant:migrate {--fresh} {--rollback}';

    protected $description = 'Run migrations on the tenant databases';

    private $migrator;

    public function __construct()
    {
        parent::__construct();
        $this->migrator = app('migrator');
    }

    public function handle()
    {
        // get all tenants
        Tenant::all()->each(function ($tenant) {
            config(['database.connections.tenant.database' => $tenant->domain]);
            $this->info('running command against database: ' . $tenant->domain . '');
            \DB::purge('tenant');
            \DB::connection('tenant')->reconnect();

            $this->migrate();
        });

    }

    private function migrate()
    {
        $this->prepareDatabase();
        if ($this->option('fresh')) {
            $this->fresh();
        } else if ($this->option('rollback')) {
            $this->rollback();
        } else {
            $this->runMigrate();
        }
    }

    protected function prepareDatabase()
    {
        $this->migrator->setConnection('tenant');

        if (!$this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }

    private function runMigrate()
    {
        $this->info('running migrations.');
        $this->migrator->run(database_path('migrations/Tenants'));
        $this->info('migrations finished.');
    }

    private function rollback()
    {
        $this->info('rolling back.');
        $this->migrator->rollback(database_path('migrations/Tenants'));
        $this->info('roll back finished.');
    }

    private function fresh()
    {
        $this->info('dropping all tables');
        \DB::getSchemaBuilder()
            ->dropAllTables();
        $this->prepareDatabase();
        $this->info('tables are dropped');
        $this->info('creating tables');
        $this->migrator->run(database_path('migrations/Tenants'));
        $this->info('all tables are created');
    }
}