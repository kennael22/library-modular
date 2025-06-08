<?php

namespace Modules\BookCopy;

use Modules\Support\BaseServiceProvider;

class BookCopyServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
