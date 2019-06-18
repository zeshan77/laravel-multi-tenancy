<?php

// Return current tenant
if (!function_exists('tenant')) {
    function tenant()
    {
        return app('tenant');
    }
}

// Check if tenant is set or not
if (!function_exists('isTenant')) {
    function isTenant(): bool
    {
        if(!app()->bound('tenant')) return false;
        
        return optional(app('tenant'))->id;
    }
}
