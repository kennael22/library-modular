<?php

namespace Modules\Member;

use Modules\Support\BaseServiceProvider;

class MemberServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
