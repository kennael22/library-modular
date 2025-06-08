<?php

namespace Modules\Book;

use Modules\Support\BaseServiceProvider;

class BookServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
