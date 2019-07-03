<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class BaseSharedModel extends Model 
{
    
    /**
     * __construct
     *
     * @param  mixed $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(app()->environment() === 'testing') {
            $this->connection = config('database.default');
        }
    }
}