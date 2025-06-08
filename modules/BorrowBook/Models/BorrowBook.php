<?php

namespace Modules\BorrowBook\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;

class BorrowBook extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes;

    protected $table = 'borrow_books';
}
