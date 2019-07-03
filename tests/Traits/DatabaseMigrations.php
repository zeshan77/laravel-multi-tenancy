<?php

namespace Tests\Traits;

use App\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait DatabaseMigrations {

    /**
     * run db migrations while running tests.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {

        $this->artisan('migrate:fresh', ['--path' => $this->migrationPath()]);

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--path' => $this->migrationPath()]);
            RefreshDatabaseState::$migrated = false;
        });
    }

    /**
     * find relative path for migration files to run based on
     * tests. If tests are running for tenant, return database/migrations/Tenants
     * otherwise database/migrations. Migration files will run based on this path.
     *
     * @return string
     */
    private function migrationPath(): string
    {
        return $this->shared ? 'database/migrations' : 'database/migrations/Tenants';
    }

}