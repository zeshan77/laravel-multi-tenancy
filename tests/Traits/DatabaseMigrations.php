<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use App\Console\Kernel;

trait DatabaseMigrations {

    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh', ['--path' => 'database/migrations/Tenants']);

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--path' => 'database/migrations/Tenants']);

            RefreshDatabaseState::$migrated = false;
        });
    }

}