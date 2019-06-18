<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use App\Services\TenantManager;
use App\Models\Shared\Tenant;

class TenantSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:seed {--tenant=} {--class=}';

    protected $description = 'Run seeds (all or specific) against tenants (all or specific)';

    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        parent::__construct();

        $this->tenantManager = $tenantManager;
    }

    public function handle()
    {
        $this->info('Seeding data');

        // run for specific tenant
        if ($this->option('tenant')) {
            $this->tenantManager->switchTenant($this->option('tenant'));
            $this->runSeed();
            $this->info('Seeding finished');
            return null;
        }

        // run for all tenants
        Tenant::all()->each(function ($tenant) {
            $this->tenantManager->switchTenant($tenant);
            $this->runSeed();
        });

        $this->info('Seeding finished');
        return null;

    }

    protected function runSeed()
    {
        if ($this->option('class'))
            Artisan::call('db:seed', ['--class' => $this->option('class')]);
        else
            Artisan::call('db:seed');
    }
}
