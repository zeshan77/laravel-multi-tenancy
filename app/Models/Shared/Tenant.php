<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'shared';

    protected $guarded = [];

}
