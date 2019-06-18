<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserService;
use App\Services\TenantManager;

class CreateUser extends Command
{

    protected $signature = 'create:user {--tenant=}';

    protected $description = 'Create a user in shared or specific tenant database';

    protected $userService;

    protected $tenantManager;

    protected $exception;

    public function __construct(UserService $userService, TenantManager $tenantManager)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->tenantManager = $tenantManager;
    }

    public function handle()
    {
        $this->info('Creating user');

        if (!$this->setTenant()) {
            $this->error($this->exception);
            return;
        }

        if(!$this->createUser()) {
            $this->error($this->exception);
        }

        $this->info('User created. You can now login using your username and password.');
        
    }

    private function createUser()
    {
        $username = $this->ask('Type your username');
        $email = $this->ask('Type your email');
        $phone_number = $this->ask('Type your phone number');
        $password = $this->secret('Type your password');

        $data = [
            'username' => $username,
            'email' => $email,
            'phone_number' => $phone_number,
            'password' => \Hash::make($password),
            'email_verified_at' => now(),
            'active' => now(),
        ];

        try {
            $this->userService->add($data);
            return true;
        } catch (\Exception $exception) {
            $this->exception = $exception->getMessage();
            return false;
        }

    }

    private function setTenant()
    {
        if (!$this->option('tenant')) return true;

        try {
            $this->tenantManager->switchTenant($this->option('tenant'));
            return true;
        } catch (\Exception $exception) {
            $this->error('Unable to switch tenant database');
            $this->exception = $exception->getMessage();
            return false;
        }
    }
}
