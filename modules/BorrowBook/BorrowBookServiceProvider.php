<?php

namespace Modules\BorrowBook;

use Modules\Support\BaseServiceProvider;

class BorrowBookServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
