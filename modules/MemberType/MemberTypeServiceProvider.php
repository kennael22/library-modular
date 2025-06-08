<?php

namespace Modules\MemberType;

use Modules\Support\BaseServiceProvider;

class MemberTypeServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
