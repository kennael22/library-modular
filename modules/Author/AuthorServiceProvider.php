<?php

namespace Modules\Author;

use Modules\Support\BaseServiceProvider;

class AuthorServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
